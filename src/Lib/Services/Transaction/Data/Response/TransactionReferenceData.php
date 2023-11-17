<?php

namespace RWBuild\Guhemba\Lib\Services\Transaction\Data\Response;

use RWBuild\Guhemba\Lib\CustomData\ResponseBaseData;

/**
 * @property string payment_reference
 */
class TransactionReferenceData  extends ResponseBaseData
{
  protected function expectedProperties(): array
  {
    return [
      "payment_reference?",
    ];
  }
}
