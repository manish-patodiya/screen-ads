<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// -----------------------------------------------------------------------------
// Get Language by ID
function get_lang_name_by_id($id)
{
    $ci = &get_instance();
    $ci->db->where('id', $id);
    return $ci->db->get('language')->row_array()['name'];
}

// -----------------------------------------------------------------------------
// Get Language Short Code
function get_lang_short_code($id)
{
    $ci = &get_instance();
    $ci->db->where('id', $id);
    return $ci->db->get('language')->row_array()['short_name'];
}

// -----------------------------------------------------------------------------
// Get Language List
function get_language_list()
{
    $ci = &get_instance();
    $ci->db->where('status', 1);
    return $ci->db->get('language')->result_array();
}

// -----------------------------------------------------------------------------
// Get country list
function get_country_list()
{
    $ci = &get_instance();
    return $ci->db->get('countries')->result_array();
}

// -----------------------------------------------------------------------------
// Get country name by ID
function get_country_name($id)
{
    $ci = &get_instance();
    return $ci->db->get_where('countries', array('id' => $id))->row_array()['name'];
}

// -----------------------------------------------------------------------------
// Get City ID by Name
function get_country_id($title)
{
    $ci = &get_instance();
    return $ci->db->get_where('countries', array('slug' => $title))->row_array()['id'];
}

// -----------------------------------------------------------------------------
// Get country slug
function get_country_slug($id)
{
    $ci = &get_instance();
    return $ci->db->get_where('countries', array('id' => $id))->row_array()['slug'];
}

// -----------------------------------------------------------------------------
// Get country's states
function get_country_states($country_id)
{
    $ci = &get_instance();
    return $ci->db->select('*')->where('country_id', $country_id)->get('states')->result_array();
}

// -----------------------------------------------------------------------------
// Get state's cities
function get_state_cities($state_id)
{
    $ci = &get_instance();
    return $ci->db->select('*')->where('state_id', $state_id)->get('cities')->result_array();
}

// Get state name by ID
function get_state_name($id)
{
    $ci = &get_instance();
    return $ci->db->get_where('states', array('id' => $id))->row_array()['name'];
}

// -----------------------------------------------------------------------------
// Get city name by ID
function get_city_name($id)
{
    $ci = &get_instance();
    return $ci->db->get_where('cities', array('id' => $id))->row_array()['name'];
}

// -----------------------------------------------------------------------------
// Get city ID by title
function get_city_slug($id)
{
    $ci = &get_instance();
    return $ci->db->get_where('cities', array('id' => $id))->row_array()['slug'];
}

/**
 * Generic function which returns the translation of input label in currently loaded language of user
 * @param $string
 * @return mixed
 */
function trans($string)
{
    $ci = &get_instance();
    return $ci->lang->line($string);
}
function prd($data)
{
    echo "<pre>";
    print_r($data);
    die;
}

function pr($data)
{
    echo "<pre>";
    print_r($data);
}

function printMenu($data, $id = 0)
{
    echo "<ul>";
    foreach ($data as $k => $v) {
        if ($v->pid == $id) {
            echo '<li><label><input type="checkbox" value="' . $v->id . '">' . $v->title . '</label></li>';
            echo printMenu($data, $v->id);
        }
    }
    echo "</ul>";
}

function createMenuArr($data, $id = 0)
{
    $arr = [];
    foreach ($data as $k => $v) {
        if ($v->pid == $id) {
            $arr[] = [
                "id" => $v->id,
                "title" => $v->title,
                "child" => createMenuArr($data, $v->id),
            ];
        }
    }
    return $arr;
}
function uc($data)
{
    $result = ucfirst(strtolower($data));
    return $result;
}