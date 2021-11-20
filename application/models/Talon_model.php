<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Talon_model extends CI_Model
{

    public function historial($id_usuario)
    {
        $this->db->select('
        talon_pago.id_talon_pago,
        usuario.rfc,
        talon_pago.fecha_pago,
        talon_pago.dias_trabajados,
        talon_pago.salario_bruto,
        talon_pago.salario_neto,
        talon_pago.impuestos_imss');
        $this->db->from('tb_talon_pago as talon_pago');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = talon_pago.id_usuario');
        $this->db->where('talon_pago.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('talon_pago.id_usuario', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function historial_empleados()
    {
        $this->db->select("
        usuario.id_usuario,
        concat(usuario.nombre,' ',usuario.apaterno,' ',usuario.amaterno) as nombre,
        usuario.url_perfil,
        usuario.rfc,
        talon_pago.id_talon_pago,
        talon_pago.fecha_pago,
        talon_pago.dias_trabajados,
        talon_pago.salario_bruto,
        talon_pago.salario_neto,
        talon_pago.impuestos_imss");
        $this->db->from('tb_talon_pago as talon_pago');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = talon_pago.id_usuario');
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('talon_pago.id_usuario', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function talon_pago_reciente($id_usuario)
    {
        $this->db->select('
        talon_pago.id_talon_pago,
        usuario.rfc,
        talon_pago.fecha_pago,
        talon_pago.dias_trabajados,
        talon_pago.salario_bruto,
        talon_pago.salario_neto,
        talon_pago.impuestos_imss');
        $this->db->from('tb_talon_pago as talon_pago');
        $this->db->join('tb_usuario as usuario', 'usuario.id_usuario = talon_pago.id_usuario');
        $this->db->where('talon_pago.id_usuario', $id_usuario);
        $this->db->where('usuario.eliminado', 0);
        $this->db->where('usuario.estatus', 'Activo');
        $this->db->order_by('talon_pago.id_usuario', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }


    public function detalle_prestamo($id_prestamo)
    {
        $this->db->select('
        prestamo.id_detalle_prestamo,
        prestamo.monto_descontado,
        prestamo.fecha_descontado,
        prestamo.fecha_solicitud,
        prestamo.pago_completado,
        prestamo.id_prestamo');
        $this->db->from('tb_detalle_prestamo as detalle');
        $this->db->where('detalle.id_prestamo', $id_prestamo);
        $this->db->order_by('detalle.id_prestamo', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : false;
    }
}
