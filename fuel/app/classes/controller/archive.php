<?php

class Controller_Archive extends Controller_ArchiveBase {

    public function action_index() {
        Response::redirect('/archive/view');
    }

    public function view($type = '', $sort = 'id', $order = 'a') {

        $offset = Input::get('o');
        $limit = 10;
        $data['order'] = ($order == 'd' ? 'a' : 'd');
        $order = ($order == 'd' ? 'desc' : 'asc');

        $data['invoices'] = parent::get_view_results($type, $offset, $limit, $sort, $order);

        $uri = Input::uri();
        $count = count($data['invoices']);

        $data['prev'] = $uri . ((isset($offset) && $offset > $limit) ? '?o=' . ($offset - $limit) : NULL);
        $data['next'] = $uri . '?o=';
        $data['next'] .= (isset($offset) ? ($offset + $offset < $count ? $limit : 0) : $limit);
        $data['base'] = 'archive/' . $type;

        $this->template->title = 'Archive &raquo; View';
        $this->template->content = View::forge('archive/view', $data);
    }
    
    public function action_single($sort = 'id', $order = 'a') {
        $this->view('single', $sort, $order);
    }

    public function action_monthly($sort = 'id', $order = 'a') {
        $this->view('monthly', $sort, $order);
    }
}
