{{-- Expects: $showBranchColumn (bool) --}}
<script>
    (function () {
        const showBranchColumn = @json((bool) ($showBranchColumn ?? false));
        const orderShowBase = @json(url(auth()->user()->branch_id ? '/branch/orders' : '/admin/orders'));
        const currencySymbol = @json(currency_symbol());

        function statusBadge(status) {
            const map = {
                completed: 'success',
                processing: 'info',
                pending: 'warning',
                shipped: 'warning',
                out_for_delivery: 'primary',
                cancelled: 'danger',
                canceled: 'danger',
            };
            const badge = map[status] || 'secondary';
            const label = (status || '').replace(/_/g, ' ');
            return `<span class="badge bg-${badge}">${label ? label.charAt(0).toUpperCase() + label.slice(1) : '-'}</span>`;
        }

        function formatMoney(amount) {
            const value = Number(amount || 0);
            return value.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 })
                + ' ' + currencySymbol;
        }

        function formatDate(iso) {
            if (!iso) {
                return '-';
            }
            const d = new Date(iso);
            if (Number.isNaN(d.getTime())) {
                return iso;
            }
            const pad = (n) => String(n).padStart(2, '0');
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate())
                + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function orderRowCells(order) {
            const cells = [
                order.id,
                escapeHtml(order.customer_name || 'Guest'),
            ];

            if (showBranchColumn) {
                cells.push(
                    order.branch_name
                        ? `<span class="badge bg-info">${escapeHtml(order.branch_name)}</span>`
                        : '<span class="text-muted">-</span>'
                );
            }

            cells.push(
                formatMoney(order.total),
                statusBadge(order.status),
                escapeHtml((order.payment_method || '-').toString().replace(/^./, (c) => c.toUpperCase())),
                formatDate(order.created_at),
                `<a href="${orderShowBase}/${order.uuid}" class="text-info mx-2 btn-sm"><i class="fa fa-eye"></i></a>`
            );

            return cells;
        }

        function findRow(table, orderId) {
            let found = null;
            table.rows({ search: 'applied' }).every(function () {
                const node = this.node();
                if (node && String(node.getAttribute('data-order-id')) === String(orderId)) {
                    found = this;
                    return false;
                }
            });
            if (found) {
                return found;
            }

            table.rows().every(function () {
                const node = this.node();
                if (node && String(node.getAttribute('data-order-id')) === String(orderId)) {
                    found = this;
                    return false;
                }
            });
            return found;
        }

        function applyTdClasses(node) {
            if (!node) {
                return;
            }
            node.querySelectorAll('td').forEach(function (td) {
                td.classList.add('align-content-center', 'text-center');
            });
        }

        function upsertOrder(table, order) {
            const cells = orderRowCells(order);
            const existing = findRow(table, order.id);

            if (existing) {
                existing.data(cells).invalidate().draw(false);
                const node = existing.node();
                if (node) {
                    node.setAttribute('data-order-id', order.id);
                    applyTdClasses(node);
                    node.classList.add('table-warning');
                    setTimeout(() => node.classList.remove('table-warning'), 2000);
                }
                return;
            }

            const row = table.row.add(cells).draw(false);
            const node = row.node();
            if (node) {
                node.setAttribute('data-order-id', order.id);
                applyTdClasses(node);
                node.classList.add('table-success');
                $(table.table().body()).prepend(node);
                setTimeout(() => node.classList.remove('table-success'), 2500);
            }
        }

        function removeOrder(table, orderId) {
            const existing = findRow(table, orderId);
            if (existing) {
                existing.remove().draw(false);
            }
        }

        $(document).ready(function () {
            const table = $('#orders-table').DataTable({
                dom: '<"top-controls row mb-3"<"col-md-4"l><"col-md-4 text-center bulk-col"><"col-md-4"f>>rtip',
                sort: false,
            });

            window.ordersDataTable = table;

            $('#orders-table tbody tr').each(function () {
                const idCell = $(this).children('td').first().text().trim();
                if (idCell) {
                    this.setAttribute('data-order-id', idCell);
                }
            });

            document.addEventListener('order:created', function (event) {
                upsertOrder(table, event.detail);
            });

            document.addEventListener('order:updated', function (event) {
                const order = event.detail;
                if (order._remove) {
                    removeOrder(table, order.id);
                    return;
                }
                upsertOrder(table, order);
            });
        });

        if (typeof handleDeleteAjax === 'function') {
            handleDeleteAjax('.delete-btn', 'Order has been deleted successfully.');
        }
    })();
</script>
