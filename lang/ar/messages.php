<?php

return [
    // Auth
    'account_created_please_verifiy_your_phone' => 'تم إنشاء الحساب بنجاح. يرجى التحقق من رقم الهاتف.',
    'logged_out_successfully' => 'تم تسجيل الخروج بنجاح.',
    'fcm_token_updated_successfully' => 'تم تحديث رمز FCM بنجاح.',
    'user_not_found' => 'المستخدم غير موجود.',
    'invalid_code' => 'رمز التحقق غير صحيح.',
    'phone_verified_successfully' => 'تم التحقق من رقم الهاتف بنجاح.',
    'you_account_is_inactivated' => 'حسابك غير مفعّل.',
    'invalid_password' => 'كلمة المرور غير صحيحة.',
    'user_loged_successfully' => 'تم تسجيل الدخول بنجاح.',
    'current_password_does_not_match' => 'كلمة المرور الحالية غير صحيحة.',
    'password_changed_successfully' => 'تم تغيير كلمة المرور بنجاح.',
    'password_reset_opt_code_sent' => 'تم إرسال رمز إعادة تعيين كلمة المرور بنجاح.',
    'verification_code_invalid' => 'رمز التحقق غير صحيح.',
    'verification_code_expired' => 'انتهت صلاحية رمز التحقق.',
    'password_reset_opt_code_is_verified' => 'تم تأكيد رمز التحقق بنجاح.',
    'invalid_or_expired_token' => 'رمز إعادة التعيين غير صالح أو منتهي الصلاحية.',
    'invalid_invitation_code' => 'رمز الدعوة غير صالح.',
    'phone_not_registered' => 'رقم الهاتف غير مسجل.',
    'phone_already_registered' => 'رقم الهاتف مسجل مسبقاً.',
    'account_deleted_successfully' => 'تم حذف حسابك بنجاح.',
    'no_authenticated_user_found' => 'لم يتم العثور على مستخدم مسجّل الدخول.',
    'unauthenticated' => 'غير مصرّح.',
    'forbidden' => 'غير مسموح.',

    // Notifications
    'notification_not_found' => 'الإشعار غير موجود.',
    'order_status_updated_title' => 'تم تحديث حالة الطلب',
    'order_status_updated_body' => 'تم تحديث حالة طلبك #:id إلى :status.',
    'new_order_title' => 'طلب جديد',
    'new_order_body' => 'طلب جديد #:id من :customer — الإجمالي :total.',
    'delivery_new_order_title' => 'طلب توصيل جديد',
    'delivery_new_order_body' => 'تم تعيين الطلب #:id إليك — :customer، الإجمالي :total.',
    'notification_marked_as_read' => 'تم تعليم الإشعار كمقروء.',
    'all_notifications_marked_as_read' => 'تم تعليم جميع الإشعارات كمقروءة.',

    // Language
    'language_not_supported' => 'اللغة غير مدعومة.',
    'language_changed_successfully' => 'تم تغيير اللغة بنجاح.',

    // Addresses
    'address_deleted_successfully' => 'تم حذف العنوان بنجاح.',

    // Cart
    'product_removed_from_cart' => 'تم إزالة المنتج من السلة.',
    'cart_cleared' => 'تم تفريغ السلة.',
    'cart_is_empty' => 'السلة فارغة.',

    // Favorites
    'removed_from_favorites' => 'تمت الإزالة من المفضلة.',
    'added_to_favorites' => 'تمت الإضافة إلى المفضلة.',

    // Reviews
    'review_submitted_pending_approval' => 'تم إرسال التقييم وهو بانتظار الموافقة.',
    'review_updated_successfully' => 'تم تحديث التقييم بنجاح.',

    // Booking lists
    'booking_list_deleted_successfully' => 'تم حذف قائمة الحجز بنجاح.',
    'booking_list_cancelled_successfully' => 'تم إلغاء قائمة الحجز بنجاح.',
    'product_in_stock_order_instead' => 'المنتج متوفر في المخزون، يمكنك طلبه الآن.',

    // Orders
    'order_not_found' => 'الطلب غير موجود.',
    'order_not_found_or_not_accessible' => 'الطلب غير موجود أو غير متاح.',
    'order_view_unauthorized' => 'غير مصرّح لك بعرض هذا الطلب.',
    'order_update_unauthorized' => 'غير مصرّح لك بتحديث هذا الطلب.',
    'order_updated_successfully' => 'تم تحديث الطلب بنجاح.',
    'order_fetched_successfully' => 'تم جلب الطلب بنجاح.',
    'orders_fetched_successfully' => 'تم جلب الطلبات بنجاح.',
    'order_not_assigned_to_you' => 'الطلب غير مُسند إليك.',
    'order_must_be_shipped_to_start_delivery' => 'يجب أن يكون الطلب في حالة تم الشحن لبدء التوصيل.',
    'order_must_be_out_for_delivery_to_complete' => 'يجب أن يكون الطلب في حالة جاري التوصيل لإتمامه.',
    'only_pending_orders_can_be_cancelled' => 'يمكن إلغاء الطلبات المعلقة فقط.',
    'order_transferred_to_admin_successfully' => 'تم تحويل الطلب إلى الإدارة بنجاح. سيتم إعادة إسناده إلى فرع مناسب.',
    'order_assigned_to_branch_successfully' => 'تم إسناد الطلب إلى الفرع بنجاح.',
    'order_transfer_pending_only' => 'لا يمكن تحويل الطلب. يمكن تحويل الطلبات المعلقة فقط إلى الإدارة.',
    'order_already_assigned_to_branch' => 'الطلب مُسند بالفعل إلى فرع. يرجى تحويله أولاً إذا أردت إعادة الإسناد.',
    'route_updated_successfully' => 'تم تحديث المسار بنجاح.',
    'shipping_address_not_found' => 'عنوان الشحن غير موجود.',
    'no_branch_found' => 'لم يتم العثور على فرع.',
    'max_order_quantity_exceeded' => 'الحد الأقصى للطلب لهذا المنتج هو :max.',
    'insufficient_variant_stock_in_branch' => 'المخزون غير كافٍ للمتغير :name في هذا الفرع.',
    'insufficient_product_stock_in_branch' => 'المخزون غير كافٍ للمنتج :name في هذا الفرع.',
    'comment_added_successfully' => 'تمت إضافة التعليق بنجاح.',
    'failed_to_add_comment' => 'فشل إضافة التعليق: :error',

    // Invoices
    'invoice_created_successfully' => 'تم إنشاء الفاتورة بنجاح.',
    'invoice_already_created_or_invalid_status' => 'تم إنشاء الفاتورة مسبقاً أو الحالة غير صالحة.',

    // Delivery
    'delivery_not_found' => 'مندوب التوصيل غير موجود.',
    'delivery_not_found_or_not_accessible' => 'مندوب التوصيل غير موجود أو غير متاح.',
    'delivery_created_successfully' => 'تم إنشاء مندوب التوصيل بنجاح.',
    'delivery_updated_successfully' => 'تم تحديث مندوب التوصيل بنجاح.',
    'delivery_deleted_successfully' => 'تم حذف مندوب التوصيل بنجاح.',
    'delivery_assigned_successfully' => 'تم إسناد مندوب التوصيل بنجاح.',
    'delivery_set_online_successfully' => 'تم تفعيل حالة الاتصال بنجاح.',
    'delivery_set_offline_successfully' => 'تم إيقاف حالة الاتصال بنجاح.',
    'delivery_branch_mismatch' => 'مندوب التوصيل غير تابع لنفس فرع الطلب.',
    'delivery_already_assigned' => 'تم إسناد مندوب التوصيل لهذا الطلب مسبقاً.',
    'delivery_offline_order_paid' => 'مندوب التوصيل غير متصل والطلب مدفوع.',
    'location_updated_successfully' => 'تم تحديث الموقع بنجاح.',
    'wallet_history_fetched_successfully' => 'تم جلب سجل المحفظة بنجاح.',
    'wallet_requests_fetched_successfully' => 'تم جلب طلبات المحفظة بنجاح.',
    'settleable_orders_fetched_successfully' => 'تم جلب الطلبات القابلة للتسوية بنجاح.',
    'wallet_request_created_successfully' => 'تم إرسال طلب المحفظة بنجاح.',
    'wallet_request_approved_successfully' => 'تمت الموافقة على طلب المحفظة بنجاح.',
    'wallet_request_rejected_successfully' => 'تم رفض طلب المحفظة بنجاح.',
    'wallet_request_already_processed' => 'تمت معالجة طلب المحفظة هذا مسبقاً.',
    'invalid_wallet_request_status' => 'حالة طلب المحفظة غير صالحة.',
    'insufficient_wallet_balance' => 'رصيد المحفظة غير كافٍ.',
    'withdrawal_amount_must_be_positive' => 'يجب أن يكون مبلغ السحب أكبر من صفر.',
    'pending_withdrawal_exists' => 'لديك بالفعل طلب سحب قيد الانتظار.',
    'order_must_be_completed_for_settlement' => 'يجب إكمال الطلب قبل التسوية.',
    'order_is_not_cod' => 'هذا الطلب ليس طلب دفع عند الاستلام.',
    'order_already_settled' => 'تمت تسوية هذا الطلب مسبقاً.',
    'settlement_request_already_exists' => 'يوجد بالفعل طلب تسوية لهذا الطلب.',

    // Branch / employees / stock
    'branch_not_found' => 'الفرع غير موجود.',
    'branch_stock_updated_successfully' => 'تم تحديث مخزون الفرع بنجاح.',
    'employee_created_successfully' => 'تم إنشاء الموظف بنجاح.',
    'employee_updated_successfully' => 'تم تحديث الموظف بنجاح.',
    'employee_deleted_successfully' => 'تم حذف الموظف بنجاح.',
    'user_is_not_an_employee' => 'المستخدم ليس موظفاً.',
    'online_status_toggled_successfully' => 'تم تبديل حالة الاتصال بنجاح.',

    // Transactions
    'transaction_updated_successfully' => 'تم تحديث المعاملة بنجاح.',
    'transaction_not_found' => 'المعاملة غير موجودة.',
    'transaction_cannot_be_paid' => 'لا يمكن دفع المعاملة. الحالة الحالية: :status.',
    'payment_failed' => 'فشل الدفع.',
    'payment_error' => 'خطأ في الدفع: :error.',

    // Coupons
    'coupon_invalid_or_inactive' => 'الكوبون غير صالح أو غير مفعّل.',
    'coupon_not_started_yet' => 'الكوبون لم يبدأ بعد.',
    'coupon_expired' => 'انتهت صلاحية الكوبون.',
    'coupon_usage_limit_reached' => 'لقد وصلت إلى الحد الأقصى لاستخدام هذا الكوبون.',
    'cart_total_below_coupon_minimum' => 'يجب أن يكون إجمالي السلة على الأقل :amount.',

    // Products
    'product_not_found' => 'المنتج غير موجود.',

    // Tickets
    'ticket_not_found' => 'التذكرة غير موجودة.',
    'ticket_update_unauthorized' => 'ليس لديك صلاحية تحديث هذه التذكرة.',
    'ticket_delete_unauthorized' => 'ليس لديك صلاحية حذف هذه التذكرة.',
    'ticket_invalid_status' => 'الحالة المحددة غير صالحة.',
    'failed_to_create_ticket' => 'فشل إنشاء التذكرة: :error.',
    'failed_to_send_ticket_reply' => 'فشل إرسال الرد: :error.',

    // General
    'something_went_wrong' => 'حدث خطأ ما: :error.',
    'failed_to_transfer_order' => 'فشل تحويل الطلب: :error.',
    'failed_to_assign_order_to_branch' => 'فشل إسناد الطلب إلى الفرع: :error.',
];
