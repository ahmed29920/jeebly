# Delivery Wallet Requests — Mobile API Guide

This document describes the **delivery wallet** flow and the new **wallet request** endpoints for the mobile delivery app.

**Base URL:** `{API_BASE}/api/delivery`  
**Auth:** `Authorization: Bearer {sanctum_token}`  
**Role:** `delivery`  
**Locale:** Optional `Accept-Language: en` or `Accept-Language: ar` (affects response `message` text)

---

## Concepts

### Wallet balance

The driver's wallet stores **delivery commission earnings**, not COD cash collected from customers.

Commission is credited automatically when the driver completes an order:

```
POST /api/delivery/orders/{order}/complete
```

The amount is calculated from admin settings:

| Setting | Values | Formula |
|---------|--------|---------|
| `delivery_man_calculation_method` | `percentage` | `final_total × (value / 100)` |
| `delivery_man_calculation_method` | `fixed` | fixed amount per order |

The complete response includes `you_will_receive` with the credited amount.

### Two request types

| Type | Purpose | Amount | Wallet effect on approval |
|------|---------|--------|---------------------------|
| `withdrawal` | Driver cashes out wallet earnings | Driver enters amount | **Decreases** wallet |
| `settlement` | Driver hands COD cash to company | Auto = order `final_total` | **No change** (COD was never in wallet) |

### Request status lifecycle

```
pending → approved
pending → rejected
```

Only admin can approve/reject (dashboard). The mobile app is **read + create** only.

---

## Endpoints overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/wallet-history` | Credit/debit history (existing) |
| `GET` | `/wallet-requests` | List driver's requests (paginated) |
| `GET` | `/settleable-orders` | COD orders eligible for settlement |
| `POST` | `/wallet-requests` | Submit withdrawal or settlement request |

---

## 1. Wallet history

```
GET /api/delivery/wallet-history?limit=15
```

### Response `200`

```json
{
  "success": true,
  "message": "Wallet history fetched successfully.",
  "data": [
    {
      "id": 1,
      "delivery_id": 3,
      "order_id": 42,
      "amount": 10.0,
      "type": "credit",
      "wallet_before": 50.0,
      "wallet_after": 60.0,
      "notes": "Payment for completed order #42",
      "created_at": "2026-07-16T10:30:00+00:00",
      "updated_at": "2026-07-16T10:30:00+00:00",
      "order": {
        "id": 42,
        "uuid": "a1b2c3d4-...",
        "status": "completed",
        "final_total": 500.0
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 1
  }
}
```

| `type` | Meaning |
|--------|---------|
| `credit` | Commission added on order complete |
| `debit` | Wallet decreased (e.g. approved withdrawal) |

---

## 2. List wallet requests

```
GET /api/delivery/wallet-requests?limit=15
```

### Response `200`

```json
{
  "success": true,
  "message": "Wallet requests fetched successfully.",
  "data": [
    {
      "id": 5,
      "delivery_id": 3,
      "type": "withdrawal",
      "amount": 100.0,
      "order_id": null,
      "status": "pending",
      "notes": "Need payout",
      "processed_at": null,
      "created_at": "2026-07-16T11:00:00+00:00",
      "updated_at": "2026-07-16T11:00:00+00:00"
    },
    {
      "id": 6,
      "delivery_id": 3,
      "type": "settlement",
      "amount": 250.0,
      "order_id": 88,
      "status": "approved",
      "notes": "Collected cash from customer",
      "admin_notes": "Received at office",
      "processed_at": "2026-07-16T12:00:00+00:00",
      "created_at": "2026-07-16T11:30:00+00:00",
      "updated_at": "2026-07-16T12:00:00+00:00",
      "order": {
        "id": 88,
        "uuid": "e5f6g7h8-...",
        "status": "completed",
        "payment_method": "cod",
        "payment_status": "paid",
        "final_total": 250.0
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 2
  }
}
```

### Field reference

| Field | Type | Notes |
|-------|------|-------|
| `type` | `withdrawal` \| `settlement` | Request kind |
| `status` | `pending` \| `approved` \| `rejected` | |
| `amount` | float | Withdrawal: user input. Settlement: order `final_total` |
| `order_id` | int \| null | Set for settlement only |
| `notes` | string \| null | Driver note |
| `admin_notes` | string \| null | Present after admin processes request |
| `processed_at` | ISO 8601 \| null | When admin approved/rejected |

---

## 3. Settleable COD orders

Use this to populate the "Settle COD" screen with orders the driver can submit for settlement.

```
GET /api/delivery/settleable-orders
```

### Eligibility rules (server-side)

An order appears here only if **all** of the following are true:

- Assigned to the authenticated driver
- `status` = `completed`
- `payment_status` = `pending`
- `payment_method` is COD-like: `cod`, `cash_on_delivery`, `cash`, `cashondelivery` (case/format insensitive)
- No existing `pending` or `approved` settlement request for that order

### Response `200`

```json
{
  "success": true,
  "message": "Settleable orders fetched successfully.",
  "data": [
    {
      "id": 88,
      "uuid": "e5f6g7h8-...",
      "status": "completed",
      "payment_method": "cod",
      "payment_status": "pending",
      "final_total": 250.0
    }
  ]
}
```

> Returns standard `OrderResource` objects. Use `id` as `order_id` when creating a settlement request.

---

## 4. Create wallet request

```
POST /api/delivery/wallet-requests
Content-Type: application/json
```

### A) Withdrawal — cash out wallet earnings

```json
{
  "type": "withdrawal",
  "amount": 100.00,
  "notes": "Optional note to admin"
}
```

| Field | Required | Rules |
|-------|----------|-------|
| `type` | Yes | Must be `withdrawal` |
| `amount` | Yes | Numeric, min `0.01`, must be ≤ current wallet balance |
| `notes` | No | Max 1000 chars |

**Business rules:**

- Only **one** pending withdrawal allowed at a time
- Amount cannot exceed current wallet balance
- On admin approval → wallet decreases + debit entry in wallet history

### B) COD settlement — hand cash to company

```json
{
  "type": "settlement",
  "order_id": 88,
  "notes": "Collected cash from customer"
}
```

| Field | Required | Rules |
|-------|----------|-------|
| `type` | Yes | Must be `settlement` |
| `order_id` | Yes | Must exist; order must belong to driver |
| `notes` | No | Max 1000 chars |

**Do not send `amount`** for settlement — the server sets it to the order's `final_total`.

**Business rules:**

- Order must be completed
- Order must be COD
- Order `payment_status` must still be `pending`
- No duplicate pending/approved settlement for the same order
- On admin approval → order `payment_status` becomes `paid` (wallet unchanged)

### Success response `201`

```json
{
  "success": true,
  "message": "Wallet request submitted successfully.",
  "data": {
    "id": 7,
    "delivery_id": 3,
    "type": "withdrawal",
    "amount": 100.0,
    "order_id": null,
    "status": "pending",
    "notes": "Optional note to admin",
    "processed_at": null,
    "created_at": "2026-07-16T13:00:00+00:00",
    "updated_at": "2026-07-16T13:00:00+00:00"
  }
}
```

---

## Validation errors `422`

Laravel validation format:

```json
{
  "message": "The amount field is required when type is withdrawal.",
  "errors": {
    "amount": ["Insufficient wallet balance."]
  }
}
```

### Error messages (EN)

| Key / scenario | Message |
|----------------|---------|
| Insufficient balance | `Insufficient wallet balance.` |
| Pending withdrawal exists | `You already have a pending withdrawal request.` |
| Amount ≤ 0 | `Withdrawal amount must be greater than zero.` |
| Order not assigned | `Order not assigned to you.` |
| Order not completed | `Order must be completed before settlement.` |
| Not COD | `This order is not a cash on delivery order.` |
| Already settled | `This order has already been settled.` |
| Duplicate settlement | `A settlement request already exists for this order.` |

Arabic equivalents are returned when `Accept-Language: ar`.

---

## Suggested mobile UI flows

### Wallet screen

```
┌─────────────────────────────┐
│  Wallet balance: 150.00     │  ← from delivery profile (`wallet` field)
├─────────────────────────────┤
│  [Withdraw]  [Settle COD]   │
│  [History]   [My Requests]  │
└─────────────────────────────┘
```

### Withdraw flow

1. Show current wallet balance
2. User enters amount + optional note
3. `POST /wallet-requests` with `type: withdrawal`
4. Show pending status; disable new withdrawal until processed
5. Poll or refresh `GET /wallet-requests` for status updates

### COD settlement flow

1. `GET /settleable-orders` → list orders with pending COD payment
2. User selects order, adds optional note
3. `POST /wallet-requests` with `type: settlement` + `order_id`
4. Show pending status per order
5. After approval, order disappears from settleable list (`payment_status` = `paid`)

### After order complete (COD)

```
1. Driver completes order
   → Commission credited to wallet (credit history)
   → Order payment_status stays "pending" for COD

2. Driver collects cash from customer (offline, outside app)

3. Driver submits settlement request when handing cash to company
```

---

## Wallet balance source

The driver's wallet is available on the delivery profile:

- `DeliveryResource.wallet` (when delivery data is loaded on user/profile endpoints)
- Updated after approved withdrawal (refresh profile or re-fetch requests/history)

---

## Related endpoints (existing)

| Endpoint | Use |
|----------|-----|
| `POST /api/delivery/orders/{order}/complete` | Triggers commission credit |
| `GET /api/delivery/wallet-history` | Show credits/debits timeline |
| `GET /api/delivery/orders?status=completed` | General completed orders list (not filtered for COD settlement) |

> Prefer `GET /settleable-orders` over filtering orders client-side for the settlement screen.

---

## Quick test checklist

- [ ] Withdraw with amount ≤ wallet → `201`, status `pending`
- [ ] Withdraw with amount > wallet → `422`
- [ ] Second withdrawal while one is pending → `422`
- [ ] Settlement for completed COD order → `201`, amount = `final_total`
- [ ] Settlement for non-COD order → `422`
- [ ] Settlement for already-paid order → `422`
- [ ] List requests shows correct `type`, `status`, `admin_notes` after admin action
- [ ] Wallet history shows `debit` after withdrawal approved (admin side)

---

## Questions?

Contact the backend team for environment base URL, test delivery accounts, and admin dashboard access to approve/reject requests (`/admin/delivery-wallet-requests`).
