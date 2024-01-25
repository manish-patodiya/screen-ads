<?php
class Playlist_model extends CI_Model
{
    public function get_playlist_types()
    {
        $this->db->select('*');
        return $this->db->get('playlist_type_master')->result();
    }

    public function add_playlist($data)
    {
        $data['created_at'] = date('Y-m-d H:m:s');
        $data['updated_at'] = date('Y-m-d H:m:s');
        $this->db->insert('playlists', $data);
        return $this->db->insert_id();
    }

    public function add_playlist_contents($data)
    {
        $this->db->insert('playlist_contents', $data);
        return $this->db->insert_id();
    }

    public function get_all_playlist()
    {
        $cid = $this->session->company_id;
        $rid = $this->session->admin_role_id;
        $this->db->select('playlists.*,users.username');
        $this->db->join('users', 'playlists.user_id=users.id');
        if ($rid != 1) {
            $this->db->where('playlists.company_id', $cid);
        }
        return $this->db->get('playlists')->result();
    }

    public function get_playlist_by_id($id)
    {
        $this->db->select('*');
        $this->db->where('playlists.id', $id);
        return $this->db->get('playlists')->row();
    }

    public function get_playlist_by_user_id($uid)
    {
        $this->db->select('*');
        $this->db->where('playlists.user_id', $uid);
        $this->db->where('playlists.company_id', $this->session->company_id);
        $this->db->order_by('id', 'desc');
        return $this->db->get('playlists')->row();
    }

    public function get_contents($plid)
    {

        $company_id = $this->session->company_id;
        $this->db->select('playlist_contents.*,media.media_file');
        $this->db->join('media', 'playlist_contents.media_id = media.id', 'left');
        $this->db->where('playlist_id', $plid);
        $playlist_contents = $this->db->get('playlist_contents')->result();
        foreach ($playlist_contents as $k => $v) {
            if (in_array($v->media_type_id, [1, 2, 3, 8, 9])) {
                $this->db->select('*')->where('media_type_id', $v->media_type_id)->where('deleted_at is NULL');
                if ($company_id) {
                    $this->db->where('company_id', $company_id);
                }
                $playlist_contents[$k]->medias = $this->db->get('media')->result();
            }
            if (in_array($v->media_type_id, [5, 6, 7])) {
                $this->db->select('*')->where('media_type', ($v->media_type_id - 4))->where('deleted_at is NULL');
                if ($company_id) {
                    $this->db->where('company_id', $company_id);
                }
                $playlist_contents[$k]->media_groups = $this->db->get('media_group_master')->result();
            }
        }
        return $playlist_contents;
    }

    public function update_playlist($data)
    {
        return $this->db->where('id', $data['id'])->update('playlists', $data);
    }

    public function deleteRow($id)
    {
        // $this->db->select('*');
        // $this->db->set(["deleted_at" => date('Y-m-d')]);
        // $this->db->where('id', $id);
        // $this->db->update('playlists');
        //$this->db->update();
        $this->db->delete('playlists', array('id' => $id));
        return $this->db->affected_rows();
    }
}