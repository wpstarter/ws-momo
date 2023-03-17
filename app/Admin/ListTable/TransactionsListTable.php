<?php

namespace App\Admin\ListTable;

use App\Banking\Model\BankTransaction;

class TransactionsListTable extends \WP_List_Table
{
    function get_bulk_actions() {

        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    public function get_columns()
    {
        return [
            'cb'=>'cb',
            'id'=>'ID',
            'tid'=>'Transaction ID',
            'bank'=>'Bank',
            'content'=>'Content',
            'amount'=>'Amount',
            'prefix'=>'Prefix',
            'order_id'=>'Order',
            'status'=>'Status',
            'created_at'=>'Created At',
            'received_at'=>'Received At',
            'notified_at'=>'Notified At',
        ];
    }
    public function prepare_items()
    {
        $this->items=BankTransaction::query()
            ->latest('created_at')
            ->paginate(15,'*','paged');
        $this->set_pagination_args(array(
            'total_items' => $this->items->total(),
            'total_pages' => $this->items->lastPage(),
            'per_page'    => $this->items->perPage(),
        ));
    }
    function column_cb($item){
        $checkbox = '<label class="screen-reader-text" for="transaction_' . $item->id . '">' . sprintf( __( 'Select %s'), $item->id ) . '</label>'
            . "<input type='checkbox' name='transactions[]' id='transaction_{$item->id}' value='{$item->id}' />";
        return $checkbox;
    }
    protected function column_default($item, $column_name)
    {
        return $item->$column_name;
    }
}