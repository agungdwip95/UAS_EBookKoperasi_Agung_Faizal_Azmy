<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
	protected $table = "pinjaman";
	protected $fillable = array('nama_pinjaman', 'besar_pinjaman', 'tgl_pengajuan_pinjaman', 'tgl_acc_pinjaman', 'tgl_pinjaman', 'tgl_pelunasan', 'anggota_id', 'angsuran_id');

	// untuk melakukan update field created_at dan updated_at secara otomatis
	public $timestamps = true;

	public function anggota()
	{
		return $this->belongsTo('App\Models\Anggota');
	}

	public function angsuran()
	{
		return $this->belongsTo('App\Models\Angsuran');
	}
}

?>

