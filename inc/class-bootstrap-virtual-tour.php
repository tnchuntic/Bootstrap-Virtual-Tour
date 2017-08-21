<?php

//Author : Thomas Chuntic
//Company: TNC Inc
//Date: 03/06/2016

class Bootstrap_Virtual_Tour {

    protected $tour_id;
    protected $tour_steps;

    public function __construct() {
        
    }

    public function get_bvt_tours() {
        $str_tour = '';
        if (function_exists('get_field')) {
            if ($list_tours = get_field('vp_list_tours', 'options')) {
                
                foreach ($list_tours as $tour) {
                    
                    //filter display if page is not empty 
                    if (empty($tour['vp_tour_display_in']) || in_array(get_the_ID(), $tour['vp_tour_display_in'])) {
                        
                        $this->tour_id = sanitize_key($tour['vp_tour_name']);
                        
                        $tour_container = ($tour['vp_container']) ? '"' . $tour['vp_container'] . '"' : "";
                        $storage = ($tour['vp_storage'] != 'default') ? 'storage: ' . $tour['vp_storage'] . ',' : '';

                        $str_tour .= '<!-- Start '.$this->tour_id.' -->';
                        
                        $str_tour .= '<script type="text/javascript">';
                        $str_tour .='var ' . $this->tour_id . ' = new Tour({name: "' . $this->tour_id . '", container: ' . $tour_container . ', autoscroll: ' . $tour['vp_autoscroll'] . ', keyboard: ' . $tour['vp_keyboard'] . ', ' . $storage . 'backdrop: ' . $tour['vp_backdrop'] . ',backdropPadding: ' . $tour['vp_bd_padding'] . ',orphan: ' . $tour['vp_orphan'] . ',duration: ' . $tour['vp_duration'] . ',delay: ' . $tour['vp_delay'] . ',basePath: "' . $tour['vp_base_path'] . '",template: ' . trim(preg_replace('/\s+/', ' ', $tour['vp_template'])) . '});' . PHP_EOL;
                       
                        $this->tour_steps = count($tour['vp_tour_steps'])-1;
                        
                        $str_tour .= PHP_EOL.$this->get_bvt_tour_steps($tour['vp_tour_steps']).PHP_EOL;
                        
                        $str_tour .='// Initialize the tour' . PHP_EOL;
                        $str_tour .=$this->tour_id . '.init();' . PHP_EOL;
//                        $str_tour .=$this->tour_id . '.start();' . PHP_EOL;
                        
                        //get welcome message popup
                        $str_tour .= $this->get_welcome_message($tour);
                        
                        $str_tour .= '</script>' . PHP_EOL;                        
                        $str_tour .= '<!-- End '.$this->tour_id.' -->';
                    }
                }
            }
        }

        return $str_tour;
    }

    public function get_welcome_message($welcome){
        $str_welcome  = '';
        //$this->tour_id.'.ended() == null
        $str_welcome .= 'if(jQuery(".home").length>0){ '.$this->tour_id.'._showPopover({title: "'.esc_html(trim(preg_replace('/\s+/', ' ', $welcome['vp_tour_welcome_title']))).'", html: true, container: "body", content: "'.trim(preg_replace('/\s+/', ' ', str_replace('"',"'",$welcome['vp_tour_welcome_msg']))).'", template: '.trim(preg_replace('/\s+/', ' ', $welcome['vp_tour_welcome_Template'])).'});}'. PHP_EOL;
        
        $str_welcome .= ''
                . 'jQuery(".tour-'.$this->tour_id.' button[data-role=\'start\']").live("click",function(){'.$this->tour_id . '.restart();});'
                . 'jQuery(".tour-'.$this->tour_id.' button[data-role=\'close\']").live("click",function(){jQuery(".tour-'.$this->tour_id.'").remove();});'. PHP_EOL;
        return $str_welcome;
    }
    
    public function get_bvt_tour_steps($steps) {
        $str_steps = '';
        foreach ($steps as $step) {

            //init value
            $duration = !empty($step['vp_step_duration']) ? 'duration: ' . $step['vp_step_duration'] . ',' : '';
            $backdropPadding = !empty($step['vp_step_bd_padding']) ? 'backdropPadding: ' . $step['vp_step_bd_padding'] . ',' : '';
            $reflex = !empty($step['vp_step_reflex']) ? 'reflex: ' . $step['vp_step_reflex'] . ',' : '';
            $template = !empty($step['vp_step_template']) ? 'template: ' . trim(preg_replace('/\s+/', ' ', $step['vp_step_template'])) : '';
            //
            //render script for step
            $str_steps .= $this->tour_id.'.addStep({path: "'.$step['vp_step_path'].'", element: "'.$step['vp_step_element'].'", placement: "'.$step['vp_step_placement'].'", title: "'.esc_html(trim(preg_replace('/\s+/', ' ', $step['vp_step_title']))).'", content: "' . esc_html(trim(preg_replace('/\s+/', ' ', $step['vp_step_content']))) . '", orphan: '.$step['vp_step_orphan'].', backdrop: '.$step['vp_step_backdrop'].','.$backdropPadding.''.$reflex.''.$duration.''.$template.'});'.PHP_EOL;
        }
        return $str_steps;
    }

}

add_action('wp_footer', function() {
    $tour = new Bootstrap_Virtual_Tour();
    echo $tour->get_bvt_tours();
}, 100);
