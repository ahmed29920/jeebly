<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>WebSocket Test - Socket.IO</title>
        <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                background: #f5f5f5;
            }
            .container {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
            h1, h2 { color: #333; }
            .form-group { margin-bottom: 15px; }
            label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            input, select, button {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
            }
            button {
                background: #007bff;
                color: white;
                border: none;
                cursor: pointer;
                margin-top: 10px;
            }
            button:hover { background: #0056b3; }
            button:disabled { background: #ccc; cursor: not-allowed; }
            .status {
                padding: 10px;
                border-radius: 4px;
                margin: 10px 0;
                font-weight: bold;
            }
            .status.connected    { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
            .status.disconnected { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
            .status.connecting   { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
            .log {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 15px;
                max-height: 400px;
                overflow-y: auto;
                font-family: monospace;
                font-size: 12px;
            }
            .log-entry {
                margin-bottom: 5px;
                padding: 5px 5px 5px 10px;
                border-left: 3px solid #007bff;
            }
            .log-entry.error   { border-left-color: #dc3545; background: #f8d7da; }
            .log-entry.success { border-left-color: #28a745; background: #d4edda; }
            .log-entry.info    { border-left-color: #17a2b8; background: #d1ecf1; }
            .row { display: flex; gap: 20px; }
            .col { flex: 1; }
            .badge {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: bold;
                background: #007bff;
                color: white;
                margin-left: 5px;
            }
        </style>
    </head>
    <body>
        <h1>WebSocket Test — Socket.IO</h1>

        <div class="container">
            <h2>Connection Settings</h2>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Socket Server URL:</label>
                        <input type="text" id="socketUrl" value="wss://socket.laravelteam.site" />
                    </div>
                    <div class="form-group">
                        <label>Project Secret:</label>
                        <input type="text" id="socketSecret" value="SSK1530" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Order ID:</label>
                        <input type="number" id="orderId" placeholder="e.g. 123" />
                    </div>
                    <div class="form-group">
                        <label>Custom Room (optional):</label>
                        <input type="text" id="customRoom" placeholder="e.g. user-45" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <button id="connectBtn" onclick="connect()">Connect</button>
                </div>
                <div class="col">
                    <button id="disconnectBtn" onclick="disconnect()" disabled>Disconnect</button>
                </div>
            </div>

            <div id="status" class="status disconnected">● Disconnected</div>
        </div>

        <div class="container">
            <h2>Emit Test Event <span class="badge">Laravel → Socket → Browser</span></h2>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Event Name:</label>
                        <input type="text" id="emitEvent" value="test.event" />
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Room to send to:</label>
                        <input type="text" id="emitRoom" placeholder="e.g. order-123 or user-45" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Data (JSON):</label>
                <input type="text" id="emitData" value='{"message":"Hello from test page!"}' />
            </div>
            <button onclick="emitEvent()" id="emitBtn" disabled>Send via Laravel /test-socket</button>
        </div>

        <div class="container">
            <h2>Event Log</h2>
            <button onclick="clearLog()" style="width:auto; padding: 5px 15px;">Clear Log</button>
            <div id="log" class="log" style="margin-top:10px;"></div>
        </div>

        <script>
            let socket = null;

            function log(message, type = "info") {
                const logDiv = document.getElementById("log");
                const entry  = document.createElement("div");
                entry.className  = `log-entry ${type}`;
                entry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
                logDiv.prepend(entry);
            }

            function clearLog() {
                document.getElementById("log").innerHTML = "";
            }

            function updateStatus(status, message) {
                const el = document.getElementById("status");
                el.className   = `status ${status}`;
                el.textContent = `● ${message}`;
            }

            function connect() {
                const url    = document.getElementById("socketUrl").value;
                const secret = document.getElementById("socketSecret").value;
                const orderId    = document.getElementById("orderId").value;
                const customRoom = document.getElementById("customRoom").value;

                if (!url || !secret) {
                    alert("Please fill in Socket Server URL and Project Secret");
                    return;
                }

                if (socket) socket.disconnect();

                updateStatus("connecting", "Connecting...");
                log("Connecting to " + url, "info");

                socket = io(url, {
                    auth: { secret },
                    transports: ["websocket"],
                });

                socket.on("connect", () => {
                    updateStatus("connected", "Connected — ID: " + socket.id);
                    log("✅ Connected: " + socket.id, "success");

                    // Join order room
                    if (orderId) {
                        socket.emit("join", "order-" + orderId);
                        log("Joined room: geeble:order-" + orderId, "info");
                    }

                    // Join custom room
                    if (customRoom) {
                        socket.emit("join", customRoom);
                        log("Joined room: geeble:" + customRoom, "info");
                    }

                    document.getElementById("connectBtn").disabled = true;
                    document.getElementById("disconnectBtn").disabled = false;
                    document.getElementById("emitBtn").disabled = false;
                });

                socket.on("connect_error", (err) => {
                    updateStatus("disconnected", "Connection Error");
                    log("❌ Error: " + err.message, "error");
                });

                socket.on("disconnect", () => {
                    updateStatus("disconnected", "Disconnected");
                    log("❌ Disconnected", "error");
                    document.getElementById("connectBtn").disabled = false;
                    document.getElementById("disconnectBtn").disabled = true;
                    document.getElementById("emitBtn").disabled = true;
                });

                // Listen for specific events
                socket.on("DeliveryStarted", (data) => {
                    log("📦 DeliveryStarted: " + JSON.stringify(data), "success");
                });

                socket.on("test.event", (data) => {
                    log("📨 test.event: " + JSON.stringify(data), "success");
                });

                // Catch all events
                socket.onAny((eventName, data) => {
                    if (!["DeliveryStarted", "test.event"].includes(eventName)) {
                        log("📨 [" + eventName + "]: " + JSON.stringify(data), "info");
                    }
                });
            }

            function disconnect() {
                if (socket) {
                    socket.disconnect();
                    socket = null;
                    updateStatus("disconnected", "Disconnected");
                    log("Disconnected manually", "info");
                    document.getElementById("connectBtn").disabled = false;
                    document.getElementById("disconnectBtn").disabled = true;
                    document.getElementById("emitBtn").disabled = true;
                }
            }

            function emitEvent() {
    const event   = document.getElementById("emitEvent").value;
    const orderId = document.getElementById("orderId").value;
    const rawRoom = document.getElementById("emitRoom").value;

    // لو الـ room فاضي خد الـ orderId تلقائي
    const room = rawRoom || (orderId ? "order-" + orderId : "");

    let data;
    try {
        data = JSON.parse(document.getElementById("emitData").value);
    } catch (e) {
        alert("Invalid JSON in data field");
        return;
    }

    log("Sending: event=" + event + " → room=" + (room || "ALL"), "info");

    fetch("/test-socket"
        + "?event=" + encodeURIComponent(event)
        + "&room="  + encodeURIComponent(room)
        + "&data="  + encodeURIComponent(JSON.stringify(data)))
        .then(r => r.json())
        .then(res => log("✅ " + JSON.stringify(res), "success"))
        .catch(err => log("❌ " + err.message, "error"));
}
        </script>
    </body>
</html>
