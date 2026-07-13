# Mobile Realtime Integration (Laravel Reverb)

The backend uses **Laravel Reverb** (Pusher protocol) on the API domain.

**Do not use Socket.IO** (`socket_io_client`). Use a Pusher-compatible client such as `pusher_channels_flutter`.

---

## Connection config

Reverb settings are **not** returned from `GET /api/settings`. The mobile team should receive them from the backend team and store them in app config / flavors:

| Constant | Production value | Server `.env` |
|----------|------------------|---------------|
| `app_key` | `geeble-key` | `REVERB_APP_KEY` |
| `host` | `geeple.site` | `REVERB_HOST` (hostname only, no `https://`) |
| `port` | `443` | `REVERB_PORT` |
| `scheme` | `https` | `REVERB_SCHEME` (`https` → encrypted / `wss`) |

**WebSocket URL (for debugging / Postman):**

```
wss://geeple.site/app/geeble-key?protocol=7&client=js&version=8.4.0&flash=false
```

**Local development (Reverb on port 8080):**

```
ws://localhost:8080/app/geeble-key?protocol=7&client=js&version=8.4.0&flash=false
```

---

## Common mistakes

### Wrong: Socket.IO on API domain

```text
WebSocketException: Connection to 'https://geeple.site:0/socket.io/...' was not upgraded to websocket
```

| Problem | Fix |
|---------|-----|
| Using `socket_io_client` | Use `pusher_channels_flutter` |
| URL contains `/socket.io/` | Reverb uses `/app/{app_key}` |
| Port `:0` | Use `443` (production) or `8080` (local Reverb) |
| `https://` as WebSocket scheme | Use `wss://` |

---

## Channels & events

Laravel broadcasts these events. On the client, **private** channels are prefixed with `private-`.

| Event | Subscribe to channel | Auth |
|-------|----------------------|------|
| `product.created` | `catalog` | None — guests supported |
| `product.updated` | `catalog` | None — guests supported |
| `product.deleted` | `catalog` | None — guests supported |
| `order.created` | `private-admin.orders` | Sanctum token |
| `order.created` | `private-branch.{branchId}.orders` | Sanctum token |
| `order.updated` | `private-admin.orders` | Sanctum token |
| `order.updated` | `private-branch.{branchId}.orders` | Sanctum token |
| `order.updated` | `private-user.{userId}.orders` | Sanctum token (order owner) |
| `order.updated` | `private-delivery.{deliveryId}` | Sanctum token (assigned delivery) |
| `delivery.order.assigned` | `private-delivery.{deliveryId}` | Sanctum token |
| Delivery tracking | `private-order-delivery.{orderId}` | Sanctum token |

> Channel names use **dots** (`admin.orders`, `branch.3.orders`, `user.5.orders`), not hyphens (`admin-orders`).

### `product.created` / `product.updated` payload

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

### `product.deleted` payload

Fired on hard delete, deactivate, or bulk deactivate/delete.

```json
{
  "product": {
    "id": 12
  }
}
```

### `order.created` / `order.updated` payload

```json
{
  "id": 42,
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "user_id": 5,
  "customer_name": "Ahmed",
  "branch_id": 3,
  "branch_name": "Main Branch",
  "delivery_id": 9,
  "total": 52000,
  "status": "processing",
  "payment_method": "cash",
  "payment_status": "paid",
  "items_count": 2,
  "created_at": "2026-06-20T12:00:00+00:00",
  "updated_at": "2026-06-20T12:30:00+00:00"
}
```

`order.updated` covers status changes, cancel, invoice → processing, payment status, assign-to-branch, and transfer-to-admin. On transfer, the previous branch/delivery channels also receive the event.

### `delivery.order.assigned` payload

Same shape as `order.updated`, sent to `private-delivery.{deliveryId}` when admin/branch assigns the order.

---

## Role → channel mapping

| User | Channel(s) |
|------|------------|
| Guest / customer | `catalog` |
| Customer (own orders) | `private-user.{user_id}.orders` |
| Branch employee (`branch_id` set) | `private-branch.{branch_id}.orders` |
| Admin / HQ employee (no `branch_id`) | `private-admin.orders` |
| Delivery | `private-delivery.{delivery_id}` |
| Customer or delivery on active order | `private-order-delivery.{orderId}` |

---

## Private channel authentication

**Endpoint:** `POST /api/broadcasting/auth`

**Headers:**
```
Authorization: Bearer {sanctum_token}
Accept: application/json
Content-Type: application/x-www-form-urlencoded
```

**Body:**
```
socket_id={from_pusher_connection}
channel_name=private-admin.orders
```

**Example response:**
```json
{
  "auth": "geeple.site:443/app/geeble-key?...",
  "channel_data": null
}
```

The Pusher SDK `onAuthorizer` callback should POST `socket_id` + `channel_name` and return the JSON response.

> `POST /api/socket/authorize` is a **legacy** room-check endpoint for the old Socket.IO server. **Do not use it for Reverb.**

---

## Recommended flows

### Guest / customer app

1. Connect Pusher/Reverb with `app_key`, `host`, `port`, `encrypted`.
2. Subscribe to public channel `catalog` (no token).
3. Listen for `product.created`, `product.updated`, `product.deleted`.
4. After login, also subscribe to `private-user.{userId}.orders` and listen for `order.updated`.

### Branch / admin app

1. Login → get Sanctum token.
2. Connect Pusher/Reverb.
3. Subscribe to the private channel for the user's role (SDK calls `/api/broadcasting/auth` automatically).
4. Listen for `order.created` and `order.updated`.

### Delivery app

1. Login → get Sanctum token.
2. Subscribe to `private-delivery.{deliveryId}`.
3. Listen for `delivery.order.assigned` and `order.updated`.

### Order delivery tracking

1. Login (customer, delivery user, admin, or employee).
2. Subscribe to `private-order-delivery.{orderId}`.
3. Listen for location / delivery events on that channel.

---

## Flutter example

**Package:** `pusher_channels_flutter`

```dart
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';

const appKey = 'geeble-key';
const host = 'geeple.site';
const port = 443;
const apiBase = 'https://geeple.site';

final pusher = PusherChannelsFlutter.getInstance();

await pusher.init(
  apiKey: appKey,
  cluster: 'mt1', // required by SDK; ignored when host is set
  host: host,
  wsPort: port,
  wssPort: port,
  encrypted: true,
  onConnectionStateChange: (current, previous) {},
  onError: (message, code, e) => debugPrint('Pusher error: $message'),
  onEvent: (event) {
    switch (event.eventName) {
      case 'product.created':
      case 'product.updated':
      case 'product.deleted':
        // JSON-decode event.data
        break;
      case 'order.created':
      case 'order.updated':
        break;
      case 'delivery.order.assigned':
        break;
    }
  },
  onAuthorizer: (channelName, socketId, options) async {
    final res = await dio.post(
      '$apiBase/api/broadcasting/auth',
      data: {
        'socket_id': socketId,
        'channel_name': channelName,
      },
      options: Options(
        headers: {'Authorization': 'Bearer $sanctumToken'},
      ),
    );
    return res.data as Map<String, dynamic>;
  },
);

await pusher.connect();

// Guest: public catalog — no auth needed
await pusher.subscribe(channelName: 'catalog');

// Admin: private channel — onAuthorizer runs automatically
// await pusher.subscribe(channelName: 'private-admin.orders');
```

Listen for the event name **`product.created`** (Laravel `broadcastAs`), not `App\Events\ProductCreated`.

---

## Testing with Postman

### 1) WebSocket — public `catalog`

1. New → **WebSocket Request**
2. URL:
   ```
   wss://geeple.site/app/geeble-key?protocol=7&client=js&version=8.4.0&flash=false
   ```
3. **Connect** → expect `pusher:connection_established` with a `socket_id`
4. Send:
   ```json
   {"event":"pusher:subscribe","data":{"channel":"catalog"}}
   ```
5. Create a new active product from the admin dashboard
6. Expect `product.created` on channel `catalog`

### 2) REST — private channel auth

```
POST https://geeple.site/api/broadcasting/auth
```

Body (`x-www-form-urlencoded`):
```
socket_id=123.456
channel_name=private-admin.orders
```

Header: `Authorization: Bearer {admin_sanctum_token}`

### 3) Subscribe to private channel (WebSocket)

After step 2, send:
```json
{
  "event": "pusher:subscribe",
  "data": {
    "channel": "private-admin.orders",
    "auth": "{auth string from step 2 response}"
  }
}
```

---

## Server checklist (backend team)

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=761827
REVERB_APP_KEY=geeble-key
REVERB_APP_SECRET=your-secret
REVERB_HOST=geeple.site
REVERB_PORT=443
REVERB_SCHEME=https
```

Also ensure:

1. Reverb process is running (`php artisan reverb:start` or supervisor).
2. Nginx / reverse proxy forwards WebSocket upgrades on port 443 to Reverb.
3. Run `php artisan config:cache` after `.env` changes.
