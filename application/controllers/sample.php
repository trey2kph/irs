<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sample extends CI_Controller { 
    public function csv_dailyapprove()
    {         

            $approvefoot = '';
            $approve_ftrans = $this->Core->get_adminapp_trans($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d'));        

            $cnt_ftotal = 0;

            foreach ($approve_ftrans as $rowftrans) :                    

                $order_farray = html_entity_decode($rowftrans->trans_order, ENT_QUOTES);
                $order_farray = unserialize($order_farray);

                $cnt_fquantity = 0;

                foreach ($order_farray as $orderfrow) :                            
                    $cnt_fquantity .= $cnt_fquantity + $orderfrow['qty'];                
                    $cnt_ftotal .= $cnt_ftotal + $orderfrow['qty'];                
                endforeach;

                $approvefoot .= ','.$cnt_fquantity;                

            endforeach;

            $csvfooter = "Total".$approvefoot.','.$cnt_ftotal;
            var_dump($csvfooter);
    }
}

/* End of file sample.php */
/* Location: ./application/controllers/sample.php */