<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Events_model extends CI_Model
{


    /*Read the data from DB */
    public function getEvents($start, $end)
    {
        $e2 = date('Y-m-d', strtotime($end . ' - 60 days'));
        $sql = "SELECT * FROM gtg_events WHERE (gtg_events.start BETWEEN ? AND ?) OR (gtg_events.end > ? ) ORDER BY gtg_events.start ASC";
        return $this->db->query($sql, array($start, $end, $e2))->result();
    }

    /*Create new events */

    public function addEvent($title, $start, $end, $description, $color)
    {

        $data = array(
            'title' => $title,
            'start' => $start,
            'end' => $end,

            'description' => $description,
            'color' => $color
        );

        if ($this->db->insert('gtg_events', $data)) {
            return true;
        } else {
            return false;
        }
    }

    /*Update  event */

    public function updateEvent($id, $title, $description, $color)
    {

        $sql = "UPDATE gtg_events SET title = ?, description = ?, color = ? WHERE id = ?";
        $this->db->query($sql, array($title, $description, $color, $id));
        return ($this->db->affected_rows() != 1) ? false : true;
    }


    /*Delete event */

    public function deleteEvent()
    {

        $sql = "DELETE FROM gtg_events WHERE id = ?";
        $this->db->query($sql, array($_GET['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    /*Update  event */

    public function dragUpdateEvent()
    {

        $sql = "UPDATE gtg_events SET  gtg_events.start = ? ,gtg_events.end = ?  WHERE id = ?";
        $this->db->query($sql, array($_POST['start'], $_POST['end'], $_POST['id']));
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}
