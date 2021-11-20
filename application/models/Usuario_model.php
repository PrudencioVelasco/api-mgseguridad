<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends CI_Model
{

    public function informacion_basica($id_usuario)
    {
        $this->db->select("
        usuario.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre_completo,
        usuario.url_perfil,
        tipo_usuario.nombre as tipo_usuario,
        usuario.sexo,
        usuario.no_telefonico,
        usuario.ciudad,
        usuario.rfc,
        usuario.correo_electronico,
        usuario.direccion");
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_tipo_usuario as tipo_usuario', 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario');
        $this->db->where('usuario.id_usuario', $id_usuario);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->where('usuario.eliminado', 0);
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function informacion_completa($id_usuario)
    {
        $this->db->select('usuario.*,tipo_usuario.nombre as tipo_usuario');
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_tipo_usuario as tipo_usuario', 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario');
        $this->db->where('usuario.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function listado()
    {
        $this->db->select("
        usuario.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre_completo,
        usuario.sexo,
        usuario.no_telefonico,
        usuario.correo_electronico,
        usuario.ciudad,
        usuario.estatus_empleado,
        usuario.estatus,
        usuario.url_perfil");
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_tipo_usuario as tipo_usuario', 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario');
        $this->db->where('usuario.eliminado', 0);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function area_trabajo($id_usuario)
    {
        $this->db->select('id_area_trabajo,puesto,servicio_asignado,lugar_servicio,turno');
        $this->db->from('tb_area_trabajo');
        $this->db->where('id_usuario', $id_usuario);
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function registrar_usuario($data)
    {
        $this->db->insert('tb_usuario', $data);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }

    public function registrar_area_trabajo($data)
    {
        return $this->db->insert('tb_area_trabajo', $data);
    }

    public function actualizar_usuario($data, $id_usuario)
    {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('tb_usuario', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function actualizar_area_trabajo($data, $id_area_trabajo)
    {
        $this->db->where('id_area_trabajo', $id_area_trabajo);
        $this->db->update('tb_area_trabajo', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function seleccionar_password($id_usuario)
    {
        $this->db->select("password");
        $this->db->from('tb_usuario');
        $this->db->where('id_usuario', $id_usuario);
        $this->db->where('estatus', 'Activo');
        $this->db->where('eliminado', 0);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function actualizar_password($data, $id_usuario)
    {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('tb_usuario', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function eliminar($data, $id_usuario)
    {
        $this->db->where('id_usuario', $id_usuario);
        $this->db->update('tb_usuario', $data);

        return ($this->db->affected_rows() >= 0) ? true : false;
    }

    public function prestamo_material_botas($id_usuario)
    {
        $this->db->select('
        botas.titulo,
        botas.motivo,
        botas.fecha_solicitud,
        botas.estatus');
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_botas as botas', 'botas.id_usuario = usuario.id_usuario');
        $this->db->where('botas.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('botas.id_botas', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function prestamo_material_uniforme($id_usuario)
    {
        $this->db->select('
        uniforme.titulo,
        uniforme.motivo,
        uniforme.fecha_solicitud,
        uniforme.estatus');
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_uniformes as uniforme', 'uniforme.id_usuario = usuario.id_usuario');
        $this->db->where('uniforme.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('uniforme.id_uniforme', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function prestamo_material_papeleria($id_usuario)
    {
        $this->db->select('
        papeleria.titulo,
        papeleria.descripcion,
        papeleria.fecha_solicitud,
        papeleria.estatus');
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_papeleria as papeleria', 'papeleria.id_usuario = usuario.id_usuario');
        $this->db->where('papeleria.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('papeleria.id_papeleria', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function prestamo_material_otros($id_usuario)
    {
        $this->db->select('
        otros.descripcion,
        otros.fecha_solicitud,
        otros.estatus');
        $this->db->from('tb_usuario as usuario');
        $this->db->join('tb_otros as otros', 'otros.id_usuario = usuario.id_usuario');
        $this->db->where('otros.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('otros.id_otros', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
