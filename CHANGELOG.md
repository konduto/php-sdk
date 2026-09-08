# Changelog

All notable changes to this project are documented in this file.

## [Unreleased]

### Added
- `Tenant` model in `src/Models/Tenant.php`.
- Support for documented Order root fields: `recurring`, `risk_level`, `sales_channel`, `scheduled`, `tenant`.
- Support for documented payment type `balance`.
- New documented fields in models:
  - `Customer`: `type`, `risk_level`, `risk_score`, `mother_name`.
  - `Payment`: `tax_id`, `cvv_result`, `avs_result`, `sha1`, `name`, `holder`, `mcc`, `mid`, `3ds_id`, `merchant_tax_id`, `voucher_type`.
  - `Device`: `provider`, `category`, `model`, `manufacturer`, `os`.
  - `Item`: `deliveryType`, `deliverySlaInMinutes`, `sellerId`, `image`.
  - `Address`: `estimatedDate`, `value`, `lat`, `lon`.
- New/expanded tests:
  - `tests/unit/PayloadFieldAlignmentTest.php`
  - updates in `tests/unit/OrderTest.php`, `tests/unit/PaymentTest.php`, `tests/unit/CustomerTest.php`, `tests/unit/AddressTest.php`.

### Changed
- `Order` now uses documented names for these objects:
  - `agent`
  - `point_of_sale`
  - `origin_account`
  - `destination_accounts`
- `Delivery` now uses documented snake_case fields:
  - `delivery_company`, `delivery_method`, `estimated_shipping_date`, `estimated_delivery_date`.
- `AgentSeller` now uses `tax_id` as the canonical serialized field.

### Fixed
- `Bank::setKeyType()` now writes `key_type` correctly.
- `Bank::setKeyValue()` now writes `key_value` correctly.

### Breaking Changes
- Removed legacy Order aliases from serialization and model methods:
  - `agentSeller` -> `agent`
  - `pointOfSale` -> `point_of_sale`
  - `bankOriginAccount` -> `origin_account`
  - `bankDestinationAccount` -> `destination_accounts`
- Removed legacy Delivery camelCase aliases from serialization:
  - `deliveryCompany` -> `delivery_company`
  - `deliveryMethod` -> `delivery_method`
  - `estimatedShippingDate` -> `estimated_shipping_date`
  - `estimatedDeliveryDate` -> `estimated_delivery_date`
- Removed `taxId` legacy alias from `AgentSeller` serialization.

