<?php

return [
    // Auth
    'account_created_please_verifiy_your_phone' => 'Account created successfully. Please verify your phone.',
    'logged_out_successfully' => 'Logged out successfully.',
    'fcm_token_updated_successfully' => 'FCM token updated successfully.',
    'user_not_found' => 'User not found.',
    'invalid_code' => 'Invalid verification code.',
    'phone_verified_successfully' => 'Phone verified successfully.',
    'you_account_is_inactivated' => 'Your account is inactive.',
    'invalid_password' => 'Invalid password.',
    'user_loged_successfully' => 'Logged in successfully.',
    'current_password_does_not_match' => 'Current password does not match.',
    'password_changed_successfully' => 'Password changed successfully.',
    'password_reset_opt_code_sent' => 'Password reset code sent successfully.',
    'verification_code_invalid' => 'Invalid verification code.',
    'verification_code_expired' => 'Verification code has expired.',
    'password_reset_opt_code_is_verified' => 'Verification code confirmed successfully.',
    'invalid_or_expired_token' => 'Invalid or expired reset token.',
    'invalid_invitation_code' => 'Invalid invitation code.',
    'phone_not_registered' => 'This phone number is not registered.',
    'phone_already_registered' => 'This phone number is already registered.',
    'account_deleted_successfully' => 'Your account has been deleted.',
    'no_authenticated_user_found' => 'No authenticated user found.',
    'unauthenticated' => 'Unauthenticated.',
    'forbidden' => 'Forbidden.',

    // Notifications
    'notification_not_found' => 'Notification not found.',
    'order_status_updated_title' => 'Order status updated',
    'order_status_updated_body' => 'Your order #:id status has been updated to :status.',
    'notification_marked_as_read' => 'Notification marked as read.',
    'all_notifications_marked_as_read' => 'All notifications marked as read.',

    // Language
    'language_not_supported' => 'Language not supported.',
    'language_changed_successfully' => 'Language changed successfully.',

    // Addresses
    'address_deleted_successfully' => 'Address deleted successfully.',

    // Cart
    'product_removed_from_cart' => 'Product removed from cart.',
    'cart_cleared' => 'Cart cleared.',
    'cart_is_empty' => 'Cart is empty.',

    // Favorites
    'removed_from_favorites' => 'Removed from favorites.',
    'added_to_favorites' => 'Added to favorites.',

    // Reviews
    'review_submitted_pending_approval' => 'Review submitted and awaiting approval.',
    'review_updated_successfully' => 'Review updated successfully.',

    // Booking lists
    'booking_list_deleted_successfully' => 'Booking list deleted successfully.',
    'booking_list_cancelled_successfully' => 'Booking list cancelled successfully.',
    'product_in_stock_order_instead' => 'Product is available in stock. You can order it now.',

    // Orders
    'order_not_found' => 'Order not found.',
    'order_not_found_or_not_accessible' => 'Order not found or not accessible.',
    'order_view_unauthorized' => 'You are not authorized to view this order.',
    'order_update_unauthorized' => 'You are not authorized to update this order.',
    'order_updated_successfully' => 'Order updated successfully.',
    'order_fetched_successfully' => 'Order fetched successfully.',
    'orders_fetched_successfully' => 'Orders fetched successfully.',
    'order_not_assigned_to_you' => 'Order is not assigned to you.',
    'order_must_be_shipped_to_start_delivery' => 'Order must be in shipped status to start delivery.',
    'order_must_be_out_for_delivery_to_complete' => 'Order must be out for delivery to complete.',
    'only_pending_orders_can_be_cancelled' => 'Only pending orders can be cancelled.',
    'order_transferred_to_admin_successfully' => 'Order transferred to admin successfully. Admin will reassign it to a suitable branch.',
    'order_assigned_to_branch_successfully' => 'Order assigned to branch successfully.',
    'order_transfer_pending_only' => 'Order cannot be transferred. Only pending orders can be transferred to admin.',
    'order_already_assigned_to_branch' => 'Order is already assigned to a branch. Please transfer it first if you want to reassign.',
    'route_updated_successfully' => 'Route updated successfully.',
    'shipping_address_not_found' => 'Shipping address not found.',
    'no_branch_found' => 'No branch found.',
    'max_order_quantity_exceeded' => 'Maximum order quantity for this product is :max.',
    'insufficient_variant_stock_in_branch' => 'Stock is not enough for variant :name in this branch.',
    'insufficient_product_stock_in_branch' => 'Stock is not enough for product :name in this branch.',
    'comment_added_successfully' => 'Comment added successfully.',
    'failed_to_add_comment' => 'Failed to add comment: :error',

    // Invoices
    'invoice_created_successfully' => 'Invoice created successfully.',
    'invoice_already_created_or_invalid_status' => 'Invoice already created or invalid status.',

    // Delivery
    'delivery_not_found' => 'Delivery not found.',
    'delivery_not_found_or_not_accessible' => 'Delivery not found or not accessible.',
    'delivery_created_successfully' => 'Delivery created successfully.',
    'delivery_updated_successfully' => 'Delivery updated successfully.',
    'delivery_deleted_successfully' => 'Delivery deleted successfully.',
    'delivery_assigned_successfully' => 'Delivery assigned successfully.',
    'delivery_set_online_successfully' => 'Delivery set online successfully.',
    'delivery_set_offline_successfully' => 'Delivery set offline successfully.',
    'delivery_branch_mismatch' => 'Delivery is not assigned to the same branch as the order.',
    'delivery_already_assigned' => 'Delivery already assigned to this order.',
    'delivery_offline_order_paid' => 'Delivery is offline but order is paid.',
    'location_updated_successfully' => 'Location updated successfully.',
    'wallet_history_fetched_successfully' => 'Wallet history fetched successfully.',

    // Branch / employees / stock
    'branch_not_found' => 'Branch not found.',
    'branch_stock_updated_successfully' => 'Branch stock updated successfully.',
    'employee_created_successfully' => 'Employee created successfully.',
    'employee_updated_successfully' => 'Employee updated successfully.',
    'employee_deleted_successfully' => 'Employee deleted successfully.',
    'user_is_not_an_employee' => 'User is not an employee.',
    'online_status_toggled_successfully' => 'Online status toggled successfully.',

    // Transactions
    'transaction_updated_successfully' => 'Transaction updated successfully.',
    'transaction_not_found' => 'Transaction not found.',
    'transaction_cannot_be_paid' => 'Transaction cannot be paid. Current status: :status.',
    'payment_failed' => 'Payment failed.',
    'payment_error' => 'Payment error: :error',

    // Coupons
    'coupon_invalid_or_inactive' => 'Invalid or inactive coupon.',
    'coupon_not_started_yet' => 'Coupon is not started yet.',
    'coupon_expired' => 'Coupon expired.',
    'coupon_usage_limit_reached' => 'You have reached the usage limit for this coupon.',
    'cart_total_below_coupon_minimum' => 'Cart total must be at least :amount.',

    // Products
    'product_not_found' => 'Product not found.',

    // Tickets
    'ticket_not_found' => 'Ticket not found.',
    'ticket_update_unauthorized' => 'You do not have permission to update this ticket.',
    'ticket_delete_unauthorized' => 'You do not have permission to delete this ticket.',
    'ticket_invalid_status' => 'Invalid status provided.',
    'failed_to_create_ticket' => 'Failed to create ticket: :error.',
    'failed_to_send_ticket_reply' => 'Failed to send reply: :error.',

    // General
    'something_went_wrong' => 'Something went wrong: :error.',
    'failed_to_transfer_order' => 'Failed to transfer order: :error.',
    'failed_to_assign_order_to_branch' => 'Failed to assign order to branch: :error.',
];
