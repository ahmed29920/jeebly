# Perfulava E-commerce Platform

## About
Perfulava is a comprehensive e-commerce platform built with Laravel that supports multi-language content, advanced product management with variants, flexible pricing, inventory management, sophisticated discount systems (offers, coupons, points), invitation/referral system, booking lists, and complete order management capabilities.

---

## Table of Contents
1. [Features Overview](#features-overview)
2. [Product Management](#product-management)
3. [Product Variants System](#product-variants-system)
4. [Variants & Variant Options](#variants--variant-options)
5. [Offers System](#offers-system)
6. [Coupons System](#coupons-system)
7. [Invitation & Points System](#invitation--points-system)
8. [Order Management](#order-management)
9. [Booking Lists](#booking-lists)
10. [Shopping Cart](#shopping-cart)
11. [User Management](#user-management)
12. [Reviews & Ratings](#reviews--ratings)
13. [Support Tickets](#support-tickets)
14. [Transactions](#transactions)
15. [Branches](#branches)
16. [Sliders](#sliders)
17. [Settings & Configuration](#settings--configuration)
18. [Export Functionality](#export-functionality)
19. [Email Notifications](#email-notifications)
20. [Technical Stack](#technical-stack)
21. [Installation](#installation)
22. [Usage Guide](#usage-guide)

---

## Features Overview

### Core Features
- ✅ **Multi-language Support** (English & Arabic)
- ✅ **Product Management** with Simple & Variable Products
- ✅ **Product Variants System** (Color, Size, etc.)
- ✅ **Variant Options Management** (Select, Radio, Checkbox, Text)
- ✅ **Offers System** (Product, Category, Cart, Shipping)
- ✅ **Coupons System** with usage limits
- ✅ **Invitation/Referral System** with points rewards
- ✅ **Points & Rewards System**
- ✅ **Booking Lists** for out-of-stock products
- ✅ **Shopping Cart** with variant selection
- ✅ **Order Management** with status tracking
- ✅ **Reviews & Ratings** system
- ✅ **Support Tickets** system
- ✅ **Branches Management**
- ✅ **Slider Management** for homepage
- ✅ **Role-based Permissions**
- ✅ **Export Functionality** (Products, Orders, Categories, Transactions)
- ✅ **PDF Invoice Generation**
- ✅ **Real-time Notifications**
- ✅ **Email Notifications** (Order, Booking, Status Updates)

---

## Product Management

### Features

#### Multi-language Support
- Products support English and Arabic translations
- Translatable fields: name, description, short_description, meta_title, meta_description, meta_keywords
- Automatic language switching based on user preferences

#### Product Types

**1. Simple Products**
- Single product with fixed price and stock
- Direct pricing and inventory management
- No variants required
- Best for: Standard products with single configuration

**2. Variable Products**
- Products with multiple variants (e.g., different sizes, colors)
- Each variant has its own price, stock, SKU, and images
- Variants are created from variant options combinations
- Best for: Products with multiple options (T-shirts in different sizes/colors)

#### Product Features
- **Flexible Pricing**: Support for percentage-based and fixed-amount discounts
- **Multiple Images**: Upload and manage multiple product images (polymorphic - supports both products and variants)
- **Category System**: Hierarchical categories with multi-category support per product
- **Product Attributes**: Customizable attributes with options or free-text values
- **Product Relations**: Related products and cross-sell products
- **SEO Optimization**: Meta titles, descriptions, and keywords
- **Status Flags**: Active, Featured, New product indicators
- **Max Order Quantity**: Limit quantity per order
- **SKU Management**: Unique product identifiers
- **Stock Management**: Individual stock tracking (simple products) or variant-level stock (variable products)
- **Bookable Products**: Mark products as bookable for out-of-stock notifications

### Usage Cases

**Creating a Simple Product:**
1. Navigate to Products > Add Product
2. **Step 1 - Basic Info**: 
   - Select Product Type: "Simple"
   - Enter product name (EN/AR), slug, SKU, descriptions
3. **Step 2 - Pricing & Stock**: 
   - Enter price and stock quantity
   - Configure discount (percentage or fixed)
   - Set max order quantity
4. **Step 3 - Images**: Upload product images
5. **Step 4 - Categories**: Assign to one or more categories
6. **Step 5 - Attributes**: Set product attributes
7. **Step 6 - Related Products**: Add related and cross-sell products
8. **Step 7 - SEO**: Configure meta fields
9. Save the product

**Creating a Variable Product:**
1. Navigate to Products > Add Product
2. **Step 1 - Basic Info**: 
   - Select Product Type: "Variable"
   - Enter product name (EN/AR), slug, SKU, descriptions
3. **Step 2 - Variants & Pricing**: 
   - Select variant options (e.g., Color, Size)
   - Click "Generate Combinations" to create all variant combinations
   - Fill in price, stock, SKU for each variant
   - Upload images for each variant (optional)
   - Set variant status (active/inactive)
4. **Step 3 - Images**: Upload main product images
5. **Step 4 - Categories**: Assign to one or more categories
6. **Step 5 - Attributes**: Set product attributes
7. **Step 6 - Related Products**: Add related and cross-sell products
8. **Step 7 - SEO**: Configure meta fields
9. Save the product

---

## Product Variants System

### Overview
The Product Variants System allows you to create products with multiple variations (e.g., different sizes, colors, materials). Each variant can have its own price, stock, SKU, and images.

### Variant Structure

**Product Variant (`product_variants` table)**
- Belongs to a Variable Product
- Has its own: name, slug, SKU, price, stock, images
- Can be active or inactive
- Contains variant values (links to variant options)

**Variant Values (`product_variant_values` table)**
- Links product variants to variant options
- Stores the selected option for each variant type
- Example: A variant might have values: Color=Red, Size=Large

### Variant Features
- ✅ **Individual Pricing**: Each variant can have different prices
- ✅ **Individual Stock**: Track stock per variant
- ✅ **Unique SKUs**: Each variant has its own SKU
- ✅ **Variant Images**: Upload specific images for each variant
- ✅ **Active/Inactive Status**: Control which variants are available
- ✅ **Automatic Combination Generation**: System generates all possible combinations from selected options
- ✅ **Price Range Display**: Variable products show price range (min-max) in listings

### Variant Management
- Variants are automatically created when generating combinations
- Can edit individual variant details (price, stock, SKU, images)
- Can delete variants that are no longer needed
- Converting variable product to simple automatically deletes all variants

### Usage Examples

**Example: T-Shirt Product**
- Product: "Classic T-Shirt"
- Variant Options: Color (Red, Blue, Green), Size (S, M, L, XL)
- Generated Variants:
  - Red - Small (SKU: TSH-RED-S, Price: 25.00, Stock: 10)
  - Red - Medium (SKU: TSH-RED-M, Price: 25.00, Stock: 15)
  - Blue - Large (SKU: TSH-BLU-L, Price: 27.00, Stock: 8)
  - ... (all 12 combinations)

---

## Variants & Variant Options

### Overview
Variants are reusable attribute types (like Color, Size, Material) that can be used across multiple products. Each variant has options (e.g., Color variant has options: Red, Blue, Green).

### Variant Types

**1. Select Dropdown**
- User selects one option from dropdown
- Best for: Color, Size, Material

**2. Radio Buttons**
- User selects one option from radio buttons
- Best for: Size, Style

**3. Checkbox**
- User can select multiple options
- Best for: Features, Add-ons

**4. Text Input**
- User enters free text
- Best for: Custom text, Engraving

### Variant Features
- ✅ **Translatable Names**: Variant names support EN/AR
- ✅ **Required/Optional**: Mark variants as required or optional
- ✅ **Active Status**: Enable/disable variants
- ✅ **Multiple Options**: Each variant can have multiple options
- ✅ **Option Codes**: Each option has a unique code
- ✅ **Reusable**: Variants can be used across multiple products

### Variant Options
- Each variant can have multiple options
- Options have: name (translatable), code
- Options are linked to variant values when creating product variants

### Usage Cases

**Creating a Variant:**
1. Navigate to Variants > Create Variant
2. Enter variant name (EN/AR): e.g., "Color"
3. Select type: Select, Radio, Checkbox, or Text
4. Set as required (optional)
5. If type is Select/Radio/Checkbox, add options:
   - Option Name (EN/AR): e.g., "Red"
   - Option Code: e.g., "RED"
6. Save the variant

**Using Variants in Products:**
1. When creating a variable product
2. Select variant options (e.g., Color, Size)
3. System generates all combinations
4. Configure each variant's price, stock, SKU, images

---

## Offers System

### Overview
The Offers System provides automatic discounts based on cart contents, products, categories, or cart total. The system automatically applies the best offer (highest discount) available.

### Offer Types

#### 1. Product Offers
- **Description**: Discount applied to specific products
- **Conditions**: Product ID must match
- **Discount Types**: Percentage, Fixed Amount, BOGO (Buy One Get One)
- **Usage**: Best for promoting specific products

**Example Use Cases:**
- "20% off on Product X"
- "Buy 1 Get 1 Free on Product Y"
- "10 EGP off on Featured Product"

#### 2. Category Offers
- **Description**: Discount applied to products in a specific category
- **Conditions**: Category ID must match
- **Discount Types**: Percentage, Fixed Amount, BOGO
- **Usage**: Best for category-wide promotions

**Example Use Cases:**
- "15% off all Electronics"
- "5 EGP off every item in Groceries category"

#### 3. Cart Offers
- **Description**: Discount applied to entire cart when minimum amount is reached
- **Conditions**: Minimum cart amount
- **Discount Types**: Percentage, Fixed Amount
- **Usage**: Best for encouraging larger purchases

**Example Use Cases:**
- "10% off orders over 500 EGP"
- "50 EGP off orders over 1000 EGP"

#### 4. Shipping Offers
- **Description**: Free shipping or shipping discount
- **Conditions**: None (applied automatically)
- **Discount Types**: Free Shipping, Percentage, Fixed Amount
- **Usage**: Best for reducing shipping friction

**Example Use Cases:**
- "Free shipping on all orders"
- "50% off shipping costs"

### Offer Features

- **Date Range**: Start date and end date for time-limited offers
- **Active/Inactive Status**: Enable/disable offers
- **Automatic Application**: System automatically applies the best offer
- **BOGO Support**: Buy One Get One functionality
- **Multiple Free Items**: Configurable setting to allow multiple free items per offer

### Offer Discount Calculation

**Discount Types:**
- **Percentage**: `discount = base_amount × (percentage / 100)`
- **Fixed**: `discount = min(base_amount, fixed_value)`
- **BOGO**: `discount = base_amount / quantity` (gives free item)

**Application Priority:**
1. System checks all active offers
2. Calculates discount for each applicable offer
3. Applies the offer with the highest discount value
4. Updates order total automatically

### Usage Cases

**Creating a Product Offer:**
1. Navigate to Offers > Create Offer
2. Set title and type: "Product"
3. Configure condition: Select product ID
4. Set discount type: Percentage or Fixed
5. Set discount value: e.g., 20 for 20% or 50 for 50 EGP
6. Set start/end dates (optional)
7. Activate the offer

**Creating a Cart Offer:**
1. Create offer with type: "Cart"
2. Set minimum cart amount: e.g., 500 EGP
3. Set discount: e.g., 10% off
4. System applies automatically when cart total reaches minimum

**BOGO Offer Example:**
1. Create Product offer
2. Select discount type: "BOGO"
3. When customer buys 1 of the product, they get 1 free
4. Free quantity is automatically added to order

---

## Coupons System

### Overview
The Coupons System provides discount codes that customers can apply at checkout. Coupons have usage limits, date restrictions, and minimum cart requirements.

### Coupon Features

#### Discount Types
- **Percentage**: Discount as percentage of cart total
  - Example: 15% off → Customer saves 15% of cart total
- **Fixed Amount**: Fixed discount amount
  - Example: 50 EGP off → Customer saves exactly 50 EGP

#### Coupon Restrictions
- **Minimum Cart Amount**: Cart must meet minimum value
- **Usage Limit**: Maximum times a coupon can be used per user
- **Date Range**: Start date and end date for validity
- **Active Status**: Enable/disable coupons
- **Per-User Tracking**: System tracks usage per user

#### Coupon Validation
When a coupon is applied, the system validates:
1. ✅ Coupon exists and is active
2. ✅ Current date is within start/end date range
3. ✅ User hasn't exceeded usage limit
4. ✅ Cart total meets minimum cart amount requirement

### Usage Cases

**Creating a Coupon:**
1. Navigate to Coupons > Create Coupon
2. Enter coupon code (unique, e.g., "SAVE20")
3. Set description (translatable for EN/AR)
4. Choose discount type: Percentage or Fixed
5. Set discount value
6. Set minimum cart amount (optional)
7. Set usage limit per user (optional)
8. Set start/end dates (optional)
9. Activate the coupon

**Applying a Coupon:**
1. Customer adds items to cart
2. At checkout, enters coupon code
3. System validates coupon
4. Discount is applied if valid
5. User can see discount amount in order summary

**Coupon Examples:**
- **WELCOME10**: 10% off, minimum 100 EGP cart, 1 use per user
- **NEWYEAR50**: 50 EGP off, minimum 200 EGP cart, unlimited uses
- **FLASH30**: 30% off, valid for 24 hours only

### Important Notes

- ✅ Coupons can be combined with Offers (offers apply first, then coupon)
- ❌ Coupons **cannot** be combined with Points redemption (either/or choice)
- ✅ Usage is tracked per user in `coupon_user` pivot table
- ✅ Coupon usage count increments with each successful application

---

## Invitation & Points System

### Overview
The Invitation & Points System rewards users for inviting others and for making purchases. Users earn points that can be redeemed for discounts.

### Invitation System

#### How It Works
1. **Invitation Code Generation**: Each user receives a unique 6-digit invitation code upon registration
2. **Invitation Usage**: New users can enter an invitation code during registration
3. **Rewards for Inviter**: 
   - Inviter's `invited_count` increments
   - When inviter reaches **10 invited users**, they receive:
     - Bonus points (configurable amount)
     - `has_invitation_discount` flag enabled
     - `invited_count` resets to 0
4. **Relationship Tracking**: System tracks `invited_by` relationship

#### Invitation Features
- ✅ Unique 6-digit invitation code per user
- ✅ Automatic relationship tracking
- ✅ Invitation counter for milestone rewards
- ✅ Bonus points after 10 successful invitations
- ✅ Special discount flag for active inviters

### Points System

#### Earning Points

**1. Order Points (Customer)**
- **Enabled**: Configurable via `allow_order_points` setting
- **Calculation**: `points_earned = order_total × (order_points_rate / 100)`
- **Example**: If rate is 2%, customer earns 2 points per 100 EGP spent

**2. Inviter Points (Referrer)**
- **Enabled**: Configurable via `allow_inviter_order_points` setting
- **Calculation**: `inviter_points = order_total × (inviter_order_points_rate / 100)`
- **Trigger**: When invited user places an order
- **Example**: If rate is 1%, inviter earns 1 point per 100 EGP from invitee's orders

**3. Invitation Milestone Bonus**
- **Trigger**: When inviter successfully invites 10 users
- **Amount**: Configurable via `invitation_discount_points` setting
- **One-time**: Given once per milestone

#### Redeeming Points

**Points to Money Conversion:**
- **Rate**: Configurable via `point_to_money_rate` setting
- **Formula**: `money_value = points / point_to_money_rate`
- **Example**: If rate is 10, then 10 points = 1 EGP

**Points Redemption Rules:**
- ✅ Can only be used if **no coupon** is applied (either/or choice)
- ✅ Maximum discount per order is configurable (`max_points_discount_per_order`)
- ✅ Points are deducted from user balance when redeemed
- ✅ If user doesn't have enough points, only available amount is used

**Redemption Process:**
1. Customer chooses to use points at checkout
2. System calculates available discount from points
3. Applies discount up to maximum allowed per order
4. Deducts equivalent points from user balance
5. Updates order total

### Usage Cases

**Scenario 1: Invitation Flow**
1. User A registers and receives invitation code: `123456`
2. User A shares code with friends
3. User B registers using code `123456`
4. User A's `invited_count` becomes 1
5. User B makes a purchase
6. User A earns inviter points (if enabled)
7. After 10 invitations, User A receives bonus points

**Scenario 2: Points Earning**
1. Customer places order for 500 EGP
2. If `order_points_rate` is 2%, customer earns 10 points
3. Points are added to customer's balance automatically

**Scenario 3: Points Redemption**
1. Customer has 100 points accumulated
2. If `point_to_money_rate` is 10, customer can redeem 10 EGP
3. At checkout, customer chooses "Use Points"
4. 10 EGP discount applied, 100 points deducted

### Points System Settings

All points-related settings are configurable in Settings:
- `allow_order_points`: Enable/disable points earning on orders
- `order_points_rate`: Percentage rate for customer points (e.g., 2 for 2%)
- `allow_inviter_order_points`: Enable/disable inviter points
- `inviter_order_points_rate`: Percentage rate for inviter points
- `point_to_money_rate`: Conversion rate (points per 1 EGP)
- `max_points_discount_per_order`: Maximum discount from points per order
- `invitation_discount_points`: Bonus points for 10 invitations milestone

---

## Order Management

### Overview
Complete order lifecycle management with status tracking, comments, notifications, and invoice generation.

### Order Features

#### Order Statuses
- **Pending**: Order placed, awaiting processing
- **Processing**: Order being prepared
- **Shipped**: Order has been shipped
- **Completed**: Order delivered to customer
- **Cancelled**: Order cancelled

#### Order Components
- **Order Items**: Products with variants, quantities, prices
- **Shipping Address**: Delivery address
- **Billing Address**: Billing/invoice address
- **Payment Method**: Selected payment method
- **Payment Status**: Payment tracking
- **Discount Breakdown**:
  - Coupon discount (if applied)
  - Points discount (if used)
  - Offer discount (if applicable)
  - Total discount amount

#### Order Calculations
```
Order Total = Sum of all items (price × quantity)
After Coupon = Order Total - Coupon Discount
After Points = After Coupon - Points Discount
After Offer = After Points - Offer Discount
Final Total = After Offer + Shipping Cost
```

#### Order Features
- ✅ **Status Updates**: Track order progress
- ✅ **Comments**: Internal notes and customer communication
- ✅ **Email Notifications**: Automatic emails on status changes
- ✅ **PDF Invoices**: Generate and download invoices
- ✅ **Order History**: Complete order history per user
- ✅ **Transaction Tracking**: Linked transaction records
- ✅ **Free Quantity Items**: Support for BOGO offers
- ✅ **UUID Tracking**: Unique order identifiers

### Usage Cases

**Placing an Order:**
1. Customer adds items to cart (with variants if applicable)
2. Selects shipping and billing addresses
3. Applies coupon (optional) or uses points (optional)
4. System automatically applies best offer
5. Confirms payment method
6. Places order
7. Receives confirmation email
8. Admin receives notification

**Processing an Order:**
1. Admin views order in dashboard
2. Updates status to "Processing"
3. Adds internal comments if needed
4. System sends email notification to customer
5. Prepares items for shipping

**Generating Invoice:**
1. Admin opens order details
2. Clicks "Generate Invoice"
3. System creates PDF invoice
4. Invoice can be downloaded or sent to customer

---

## Booking Lists

### Overview
The Booking Lists feature allows customers to request notifications when out-of-stock products become available. Customers can book products and receive email notifications when stock is replenished.

### Booking Features

#### Booking Statuses
- **Pending**: Booking request created, awaiting stock
- **Confirmed**: Booking confirmed by admin
- **Fulfilled**: Product available, customer notified
- **Cancelled**: Booking cancelled

#### Booking Components
- **Product**: The product being booked
- **User**: Customer who made the booking
- **Quantity**: Requested quantity
- **Expected Date**: Expected availability date (set by admin)
- **Notified**: Whether customer has been notified
- **Status**: Current booking status

### Booking Workflow

**1. Customer Creates Booking:**
- Customer views out-of-stock product
- Clicks "Book This Product" or similar
- System validates: Product must be out of stock
- Booking created with status "Pending"
- Customer receives confirmation email

**2. Admin Management:**
- Admin views all bookings in dashboard
- Can update expected date
- Can update status (Pending → Confirmed → Fulfilled)
- Can cancel bookings

**3. Stock Notification:**
- When product stock is replenished
- System checks for pending/confirmed bookings
- Sends email notification to customers
- Updates booking status to "Fulfilled"
- Marks booking as notified

### Usage Cases

**Creating a Booking:**
1. Customer views product that is out of stock
2. Clicks "Notify Me When Available"
3. System creates booking request
4. Customer receives confirmation email
5. Admin can set expected date

**Managing Bookings:**
1. Admin navigates to Booking Lists
2. Views all bookings with filters
3. Updates expected date for bookings
4. Updates status as stock becomes available
5. System sends notifications automatically

**Email Notifications:**
- **Booking Created**: Sent when customer creates booking
- **Product Available**: Sent when product stock is replenished

---

## Shopping Cart

### Features
- ✅ **Variant Selection**: Choose product variants (for variable products)
- ✅ **Quantity Management**: Add, update, remove items
- ✅ **Price Calculation**: Automatic calculation based on variants
- ✅ **Discount Preview**: Shows applicable discounts
- ✅ **Cart Persistence**: Saved per user

### Cart Item Structure
- User ID
- Product ID
- Variant ID (for variable products)
- Quantity

### Usage Cases
1. Customer browses products
2. For variable products: Selects variant (e.g., Color: Red, Size: Large)
3. Adds to cart with quantity
4. Can update quantity or remove items
5. Cart persists across sessions
6. At checkout, cart items become order items

---

## User Management

### Features

#### User Roles & Permissions
- **Role-based Access Control**: Using Spatie Permission package
- **Predefined Roles**: Admin, User (customizable)
- **Permissions**: Granular permissions for each module:
  - Products, Categories, Orders, Coupons, Offers
  - Users, Roles, Settings, Tickets, Reviews, Transactions
  - Variants, Booking Lists, Branches, Sliders
- **Middleware Protection**: Routes protected by role/permission checks

#### User Features
- ✅ **Profile Management**: Name, email, phone, gender, image
- ✅ **Address Management**: Multiple shipping/billing addresses
- ✅ **Order History**: View all past orders
- ✅ **Favorites/Wishlist**: Save favorite products
- ✅ **Points Balance**: View and track points
- ✅ **Invitation Code**: Share code with friends
- ✅ **Verification System**: Email/phone verification support
- ✅ **Booking History**: View all booking requests

#### User Registration Flow
1. User provides: name, email, phone, password, gender (optional)
2. Optional: Enter invitation code
3. System generates unique invitation code
4. User receives verification code
5. Account activated after verification
6. If invitation code used, inviter benefits apply

---

## Reviews & Ratings

### Features
- ✅ **Product Reviews**: Customers can review products
- ✅ **Rating System**: 1-5 star ratings
- ✅ **Review Comments**: Text comments with ratings
- ✅ **Admin Approval**: Reviews require admin approval before publishing
- ✅ **Status Management**: Pending, Approved, Rejected
- ✅ **Average Rating**: Automatic calculation per product

### Usage Cases

**Submitting a Review:**
1. Customer purchases and receives product
2. Can submit review with rating (1-5 stars) and comment
3. Review status: "Pending"
4. Admin reviews and approves/rejects
5. Approved reviews appear on product page
6. Product average rating updates automatically

**Managing Reviews:**
1. Admin views all reviews in dashboard
2. Can approve, reject, or delete reviews
3. Bulk actions available
4. Reviews affect product ratings

---

## Support Tickets

### Features
- ✅ **Ticket Creation**: Users can create support tickets
- ✅ **Ticket Status**: Open, In Progress, Resolved, Closed
- ✅ **Messages**: Thread-based conversation system
- ✅ **Attachments**: Support for file attachments
- ✅ **Admin Replies**: Admins can reply to tickets
- ✅ **UUID Tracking**: Unique ticket identifiers

### Usage Cases

**Creating a Ticket:**
1. User navigates to support section
2. Creates ticket with subject, description
3. Can attach files if needed
4. Ticket status: "Open"
5. Admin receives notification

**Responding to Ticket:**
1. Admin views ticket in dashboard
2. Reads user's message
3. Replies with solution or questions
4. Updates status as needed
5. User receives notification of reply

---

## Transactions

### Features
- ✅ **Payment Tracking**: Track all payment transactions
- ✅ **Transaction Status**: Pending, Completed, Failed, Refunded
- ✅ **Payment Methods**: Various payment method support
- ✅ **Reference Numbers**: External payment reference tracking
- ✅ **Raw Response Storage**: Store payment gateway responses
- ✅ **UUID Tracking**: Unique transaction identifiers
- ✅ **Order Linking**: Transactions linked to orders

### Transaction Creation
- Transactions are automatically created when orders are placed
- Linked to user and order
- Amount matches order final total
- Currency support (default: EGP)

---

## Branches

### Features
- ✅ **Branch Management**: Create and manage store branches
- ✅ **Branch Information**: Name, address, contact details
- ✅ **Order Assignment**: Orders can be assigned to specific branches
- ✅ **Multi-branch Support**: Support for multiple physical locations

### Usage Cases

**Creating a Branch:**
1. Navigate to Branches > Create Branch
2. Enter branch name, address, contact information
3. Set branch status (active/inactive)
4. Save branch

**Assigning Orders to Branches:**
1. When creating order, select branch
2. Order is linked to branch
3. Branch can manage its orders

---

## Sliders

### Features
- ✅ **Homepage Sliders**: Create image sliders for homepage
- ✅ **Multiple Images**: Upload multiple slider images
- ✅ **Link Support**: Sliders can link to products or pages
- ✅ **Order Management**: Control slider display order
- ✅ **Active Status**: Enable/disable sliders

### Usage Cases

**Creating a Slider:**
1. Navigate to Sliders > Create Slider
2. Upload slider image
3. Set title and description (optional)
4. Add link URL (optional)
5. Set display order
6. Activate slider

---

## Settings & Configuration

### Available Settings

#### Application Settings
- `app_name`: Application name
- `app_logo`: Application logo image
- `app_icon`: Application icon/favicon

#### Shipping Settings
- `shipping_cost`: Default shipping cost amount

#### Points & Rewards Settings
- `allow_order_points`: Enable/disable points earning on orders (boolean)
- `order_points_rate`: Percentage rate for customer points (e.g., 2 for 2%)
- `allow_inviter_order_points`: Enable/disable inviter points (boolean)
- `inviter_order_points_rate`: Percentage rate for inviter points
- `point_to_money_rate`: Conversion rate (points per 1 EGP, e.g., 10 means 10 points = 1 EGP)
- `max_points_discount_per_order`: Maximum discount from points per order (in EGP)

#### Invitation Settings
- `invitation_discount_points`: Bonus points awarded when user invites 10 people

#### Offer Settings
- `allow_more_than_one_free_item`: Allow multiple free items in BOGO offers (boolean)

### Usage Cases

**Configuring Points System:**
1. Navigate to Settings
2. Enable "Allow Order Points"
3. Set "Order Points Rate" to 2 (means 2% - 2 points per 100 EGP)
4. Enable "Allow Inviter Order Points"
5. Set "Inviter Order Points Rate" to 1 (means 1% for referrers)
6. Set "Point to Money Rate" to 10 (10 points = 1 EGP)
7. Set "Max Points Discount Per Order" to 100 EGP
8. Save settings

**Configuring Shipping:**
1. Navigate to Settings
2. Set "Shipping Cost" to desired amount (e.g., 10 EGP)
3. Save settings
4. All orders will include this shipping cost

---

## Export Functionality

### Supported Exports

#### 1. Products Export
- Export all products to Excel
- Includes: name, SKU, price, stock, categories, variants, etc.
- Useful for: Backup, analysis, bulk editing preparation

#### 2. Orders Export
- Export all orders to Excel
- Includes: order details, customer info, totals, status
- Useful for: Accounting, reporting, analysis

#### 3. Categories Export
- Export all categories to Excel
- Includes: category names, hierarchies
- Useful for: Backup, migration

#### 4. Transactions Export
- Export all transactions to Excel
- Includes: transaction details, amounts, status
- Useful for: Financial reporting, reconciliation

### Usage Cases

**Exporting Products:**
1. Navigate to Products > Export
2. System generates Excel file
3. Download file
4. Use for analysis or backup

**Exporting Orders for Accounting:**
1. Navigate to Orders > Export
2. Filter by date range if needed
3. Export to Excel
4. Use for financial reporting

---

## Email Notifications

### Overview
The platform sends automatic email notifications for various events to keep customers and admins informed.

### Email Types

#### Order Emails
- **Order Created**: Sent to customer when order is placed
- **Order Status Updated**: Sent when order status changes
- **Order Comment**: Sent when admin adds comment to order

#### Booking Emails
- **Booking Created**: Sent to customer when booking is created
- **Product Available**: Sent when booked product becomes available

### Email Features
- ✅ **Markdown Templates**: Beautiful, responsive email templates
- ✅ **Multi-language Support**: Emails support EN/AR
- ✅ **Branding**: Includes app logo and name
- ✅ **Action Buttons**: Links to view orders, products, etc.
- ✅ **Null Safety**: Handles deleted products/users gracefully

### Email Templates
All email templates follow consistent design pattern:
- Logo header
- Clear subject and greeting
- Information panels
- Action buttons
- Footer with branding

---

## Technical Stack

### Backend
- **Framework**: Laravel 10.x
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Sanctum (API), Session (Web)

### Frontend
- **Templating**: Blade
- **Styling**: Bootstrap, Custom SCSS
- **JavaScript**: jQuery, Vanilla JS
- **Assets**: Vite

### Key Packages
- **Spatie Translatable**: Multi-language support
- **Spatie Permission**: Role & Permission management
- **Laravel Sanctum**: API authentication
- **Maatwebsite Excel**: Data import/export
- **DomPDF**: PDF invoice generation
- **Pusher**: Real-time notifications (optional)

---

## Installation

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL/SQLite

### Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd laeij
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** in `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laeij
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

6. **Build assets**
```bash
npm run build
# Or for development:
npm run dev
```

7. **Create storage link**
```bash
php artisan storage:link
```

8. **Start the server**
```bash
php artisan serve
```

9. **Access the application**
- Dashboard: `http://localhost:8000/admin/login`
- Default admin credentials: Check seeders or create admin user

---

## Usage Guide

### Admin Dashboard Access
1. Navigate to `/admin/login`
2. Login with admin credentials
3. Access dashboard with full permissions

### Common Workflows

#### Setting Up Products
1. Create Variants first (Color, Size, etc.)
2. Create Variant Options for each variant
3. Create Categories
4. Create Products (Simple or Variable)
5. For Variable Products: Generate variant combinations
6. Upload images
7. Set attributes and SEO

#### Creating Promotions
1. **Offers**: Create automatic discounts
2. **Coupons**: Create discount codes for customers
3. Set date ranges and conditions
4. Activate promotions

#### Managing Orders
1. View orders in dashboard
2. Update order status
3. Add comments for internal notes
4. Generate invoices when needed
5. Track payment status

#### Managing Bookings
1. View booking lists in dashboard
2. Update expected dates
3. Update booking status
4. System sends notifications when stock available

#### Configuring Points System
1. Go to Settings
2. Enable points earning
3. Set conversion rates
4. Configure invitation rewards
5. Set maximum redemption limits

---

## Database Structure

### Key Tables

#### users
- User accounts with invitation codes, points, roles
- Fields: name, email, phone, invitation_code, invited_by, points, role

#### products
- Main product information
- Translatable fields: name, description, short_description, meta fields
- Fields: type (simple/variable), sku, price, stock, discount, discount_type, max_order_quantity, manage_stock, is_bookable

#### product_variants
- Product variants for variable products
- Fields: product_id, name, slug, sku, price, stock, is_active
- Translatable: name

#### variants
- Reusable variant types (Color, Size, etc.)
- Fields: name, type (select/radio/checkbox/text), is_active, is_required
- Translatable: name

#### variant_options
- Options for variants (Red, Blue, Large, Small, etc.)
- Fields: variant_id, name, code
- Translatable: name

#### product_variant_values
- Links product variants to variant options
- Fields: product_variant_id, variant_option_id, value

#### product_images
- Polymorphic table for product and variant images
- Fields: imageable_id, imageable_type, path

#### booking_lists
- Booking requests for out-of-stock products
- Fields: product_id, user_id, quantity, expected_at, status, notified

#### orders
- Order records
- Fields: user_id, status, total, discount, coupon_id, offer_id, final_total, shipping_cost, uuid

#### order_items
- Order line items
- Fields: order_id, product_id, variant_id, quantity, price, free_quantity

#### coupons
- Discount coupon codes
- Fields: code, type, discount_value, min_cart_amount, usage_limit, start_date, end_date

#### offers
- Automatic discount offers
- Fields: title, type, condition (JSON), discount_type, discount_value, start_date, end_date

#### transactions
- Payment transaction records
- Fields: user_id, order_id, amount, status, payment_method, transaction_id, uuid

#### tickets
- Support ticket system
- Fields: user_id, subject, status, description, attachments (JSON), uuid

#### reviews
- Product reviews and ratings
- Fields: product_id, user_id, rating, comment, status

#### branches
- Store branches
- Fields: name, address, contact information

#### sliders
- Homepage sliders
- Fields: image, title, description, link, order, is_active

---

## API Endpoints

The platform provides RESTful API endpoints for:
- Product listing and details (with variants)
- Cart management (with variants)
- Order processing
- User authentication
- Categories
- Reviews
- Coupons
- Points
- Booking Lists
- Addresses
- Tickets

API authentication is handled via Laravel Sanctum.

---

## Key Service Methods

### ProductService Methods

#### `store(array $data): Product`
Creates a new product (simple or variable) with all relationships.

**For Variable Products:**
- Generates variant combinations from selected options
- Creates product variants with prices, stock, SKUs
- Handles variant images

#### `update(Product $product, array $data): Product`
Updates product and handles type conversion.

**Type Conversion:**
- Variable → Simple: Deletes all variants and their images
- Simple → Variable: Allows adding variants

#### `syncProductVariants(Product $product, array $data): void`
Synchronizes product variants for variable products.

### BookingListService Methods

#### `create(array $data): BookingList`
Creates a booking request and sends confirmation email.

**Validations:**
- Product must exist
- Product must be out of stock
- Sends email notification to customer

### OfferService Methods

#### `getActiveOffers(): Collection`
Returns all currently active offers (within date range and enabled).

#### `applyBestOffer(Collection $cartItems, float $subtotal): array`
Applies the best available offer from all active offers.
- Returns: offer object, discount amount, free quantity, free quantity products

### CouponService Methods

#### `validateCoupon(string $code, float $cartTotal): Coupon`
Validates a coupon code and returns the coupon if valid.

**Validations:**
- Coupon exists and is active
- Current date within start/end range
- User hasn't exceeded usage limit
- Cart total meets minimum requirement

### OrderService Methods

#### `createOrder(int $userId, ...): Order`
Creates a new order with all discount calculations.

**Order Creation Flow:**
1. Gets cart items (with variants)
2. Calculates subtotal
3. Applies coupon (if provided)
4. Applies points discount (if requested)
5. Applies best offer automatically
6. Adds shipping cost
7. Creates order record
8. Creates order items
9. Creates transaction
10. Adds points to user and inviter (if enabled)
11. Sends notifications
12. Clears cart

---

## Discount Application Priority

When multiple discounts are available, they are applied in this order:

1. **Product Discounts**: Applied first at product level (from product.discount field)
2. **Coupon Discount**: Applied to subtotal (if coupon code provided)
3. **Points Discount**: Applied after coupon (if points used and no coupon)
4. **Offer Discount**: Applied last (best offer automatically selected)
5. **Shipping Cost**: Added to final total

**Important Rules:**
- ✅ Coupons and Offers can be combined
- ❌ Coupons and Points **cannot** be combined (either/or)
- ✅ Offers are always applied automatically
- ✅ Product discounts apply at item level
- ✅ Best offer is automatically selected (highest discount)

---

## Troubleshooting

### Variants Not Showing
- Ensure variants are created and set as active
- Check variant options are added
- Verify product type is "variable"
- Check variant combinations are generated

### Discounts Not Applying
- Check offer/coupon is active
- Verify date ranges are valid
- Ensure cart meets minimum requirements
- Check user hasn't exceeded usage limits

### Points Not Crediting
- Verify `allow_order_points` setting is enabled
- Check `order_points_rate` is configured
- Ensure order was completed successfully

### Booking Notifications Not Sending
- Check email configuration in `.env`
- Verify booking status is correct
- Check product stock is actually replenished
- Review email logs

---

## Best Practices

### Product Management
- Create variants and options before products
- Use descriptive variant names and option codes
- Set appropriate stock levels
- Use SEO fields for better search visibility
- Upload high-quality product images
- Use variant images for better product presentation

### Offers & Coupons
- Set clear date ranges for time-limited promotions
- Monitor usage to prevent abuse
- Test offers before activating
- Use descriptive titles and conditions

### Points System
- Set reasonable conversion rates
- Monitor point accumulation
- Set maximum redemption limits
- Communicate points value to customers

### Order Management
- Update status promptly
- Add helpful comments for team
- Send notifications for status changes
- Generate invoices timely

### Booking Management
- Set realistic expected dates
- Update bookings when stock arrives
- Monitor booking requests regularly

---

## Future Improvements

Potential enhancements for the platform:
- [ ] Advanced analytics dashboard
- [ ] Multi-currency support
- [ ] Advanced reporting
- [ ] Mobile app API enhancements
- [ ] Bulk operations for variants
- [ ] Advanced inventory management
- [ ] Product bundles
- [ ] Quantity-based pricing tiers
- [ ] Advanced search and filtering

---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Support

For issues and questions:
- Check this documentation first
- Review code comments in services and repositories
- Check Laravel logs: `storage/logs/laravel.log`
- Contact the development team

---

## Changelog

### Recent Updates
- **2025-11-06**: Booking Lists feature added
- **2025-11-05**: Product Variants System implemented (Simple/Variable products)
- **2025-11-04**: Variants and Variant Options system added
- **2025-11-02**: Points system added
- Enhanced offer system with BOGO support
- Improved coupon usage tracking
- Added invitation milestone rewards
- Email notifications for bookings
- Polymorphic image system for products and variants

---

*Last Updated: 2025-11-06*
