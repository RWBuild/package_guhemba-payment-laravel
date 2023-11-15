<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property numeric id
 */
class TransactionFeeData  extends ResponseBaseData
{
  protected function expectedProperties(): array
  {
    return [
      "id?",
      "transaction_id?",
      "agent_fee_amount?",
      "sales_fee_amount?",
      "agency_fee_amount?",
      "transfer_fee_amount?",
      "discount_amount?",
      "agent_credit_amount?",
      "created_at?",
      "updated_at?",
      "vat_amount?",
      "vat_is_inclusive?",
      "has_vat?",
      "redeem_discount_amount?",
      "coupon_discount_amount?",
      "point_discount_amount?",
      "refund_fee_amount?",
    ];
  }
}
