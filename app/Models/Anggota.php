<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
	protected $table = "anggota";
	protected $fillable = array('nama', 'alamat', 'tgl_lahir', 'tempat_lahir', 'jenis_kelamin', 'status', 'no_tlp', 'user_id');

	// untuk melakukan update field created_at dan updated_at secara otomatis
	public $timestamps = true;

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function simpanans()
	{
		return $this->hasMany('App\Models\Simpanan');
	}

    public function pinjamans()
	{
		return $this->hasMany('App\Models\Pinjaman');
	}

	public function angsurans(){
		return $this->hasMany('App\Models\Angsuran');
	}
}

?>

