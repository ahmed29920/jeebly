# Mobile Socket Integration

## Purpose
This document explains how the mobile app should subscribe to realtime events for:
- New products
- New orders

## Socket Server
- Use `SOCKET_SERVER_URL` provided by backend.
- Connect with Socket.IO and pass the project secret in `auth.secret`.

Example:
```js
const socket = io(SOCKET_SERVER_URL, {
  auth: { secret: SOCKET_PROJECT_SECRET },
  transports: ['websocket'],
});
```

## Room Authorization API
- Endpoint: `POST /api/socket/authorize`
- **No auth required for `catalog`** (guest mode supported).
- **Auth required** for all other rooms (`admin-orders`, `branch-*-orders`, `order-*`, etc.).

Body:
```json
{
  "room": "catalog"
}
```

### Public catalog room (guest mode)
Guests do **not** need a Sanctum token.

Response:
```json
{
  "allowed": true,
  "guest": true,
  "user": null
}
```

After connect, join directly:
```js
socket.emit('join', 'catalog');
```

Optional: you may still call `/api/socket/authorize` for `catalog` without a token; it will return `allowed: true`.

### Protected rooms (logged-in only)
Headers:
- `Authorization: Bearer {sanctum_token}`

Body example:
```json
{
  "room": "admin-orders"
}
```

Response:
```json
{
  "allowed": true,
  "user": {
    "id": 1,
    "name": "User Name",
    "role": "admin"
  }
}
```

Only join protected rooms when `allowed = true`.

## Realtime Events

### 1) New Product Event
- Event name: `product.created`
- Room: `catalog`
- Audience: customer app (guest + logged-in users)

Payload:
```json
{
  "product": {
    "id": 12,
    "name": { "en": "Milk", "ar": "حليب" },
    "slug": "milk",
    "sku": "MILK-001",
    "price": 2500,
    "type": "simple",
    "is_active": true,
    "is_new": true,
    "is_featured": false,
    "thumb_image": "https://example.com/storage/..."
  }
}
```

### 2) New Order Event
- Event name: `order.created`
- Rooms:
  - Admin/HQ: `admin-orders`
  - Branch team: `branch-{branchId}-orders`
- Requires login

Payload:
```json
{
  "id": 42,
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "customer_name": "Ahmed",
  "branch_id": 3,
  "branch_name": "Main Branch",
  "total": 52000,
  "status": "pending",
  "payment_method": "cash",
  "payment_status": "pending",
  "items_count": 2,
  "created_at": "2026-06-20T12:00:00+00:00"
}
```

## Role-to-Room Mapping
- Guest or `user`:
  - Join `catalog` (no token required)
- `employee` with `branch_id`:
  - Join `branch-{branch_id}-orders` (token required)
- `admin` or HQ employee (no `branch_id`):
  - Join `admin-orders` (token required)

## Existing Tracking Room
Order delivery tracking already uses:
- `order-{orderId}` (token required)

## Recommended Flow

### Guest customer app
1. Open socket connection with project secret.
2. Join `catalog` directly (no `/api/socket/authorize` needed).
3. Listen for `product.created`.

### Logged-in customer app
1. Open socket connection.
2. Join `catalog`.
3. Listen for `product.created`.
4. For order tracking, authorize and join `order-{orderId}`.

### Branch / admin app
1. Login and get Sanctum token.
2. Open socket connection.
3. Authorize each required room via `/api/socket/authorize`.
4. Join approved rooms.
5. Listen for `order.created`.
6. On logout: leave rooms / disconnect socket.

## Quick Listener Example
```js
socket.on('connect', () => {
  socket.emit('join', 'catalog');
});

socket.on('product.created', (data) => {
  console.log('New product:', data.product);
});

socket.on('order.created', (data) => {
  console.log('New order:', data.id, data.status);
});
```
