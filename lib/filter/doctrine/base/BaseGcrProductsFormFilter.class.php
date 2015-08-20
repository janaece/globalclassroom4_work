<?php

abstract class BaseGcrProductsFormFilter extends BaseFormFilterDoctrine
{
  public function getModelName()
  {
    return 'GcrProducts';
  }

}
