# Customer Order Cancel

This module allow cancel not paid orders by customer

## Installation

### Manually

* Copy the module into `<thelia_root>/local/modules/` directory and be sure that the name of the module is CustomerOrderCancel.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

`composer require zorn-v/thelia-customer-order-cancel-module:~1.0`

## Usage

Module provides controller for url `/account/order/cancel/{orderId}`

In your template you must use `{token_url}` helper. For example in `account.html` template

```smarty
...
{loop type="order-status" name="order.status" id={$STATUS}}
    ...
    {if $CODE == 'not_paid'}
      <a href="{token_url path="/account/order/cancel/%id" id=$ID}" data-confirm="Do you really want to cancel this order ?">
        <i class="fa fa-ban"></i>
      </a>
    {/if}
{/loop}
...
```
