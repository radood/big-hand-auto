<?php
/**
 * Created by PhpStorm.
 * User: Anil.Bheema
 * Date: 9/2/2016
 * Time: 12:16 PM
 */

namespace Objects;

trait Common
{

    protected $opt_select = '#select2-campaign-offerId-container';
    protected $opt_select_new = '.select2-results__option';
    protected $opt_select_nested = '.select2-results__options--nested li';
    protected $opt_select_tools = ".list-group-item";
    protected $opt_select_textField = '/html/body/span[3]/span/span[1]/input';
    protected $opt_highlighted = '.select2-results__option.select2-results__option--highlighted';
    protected $inputField = "/span/span/span/ul/li/input";
    protected $inputFieldSmall = "/span/span/ul/li/input";
    protected $opt_value = "/div[@class='select2-result-label']";
    protected $opt_sub_select = "/div[@class='select2-result-label']";
    protected $opt_value_1 = "//ul[@class='select2-results']/li";
    protected $opt_value_2 = "/ul/li";
    protected $opt_select_1 = 'li.select2-result-unselectable>div';
    protected $opt_select_2 = 'li.select2-result-selectable>div';
    protected $opt_store_loc = "//div[@id='select2-drop']/ul/li";

    protected $btn_next = "a#DataTables_Table_0_next[class='next paginate_button']";
    protected $btn_next_disabled = ".next.paginate_button.paginate_button_disabled";
    protected $tbl_result_row_x = "//tbody/tr";
    protected $tbl_result_row = 'tbody>tr';
    protected $no_data_in_table = '.dataTables_empty';
    protected $lbl_loading = '.loading-text';

}