<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
	protected $table = "petugas";
	protected $fillable = array('nama', 'alamat', 'jenis_kelamin', 'tgl_lahir', 'tempat_lahir', 'user_id');

	// untuk melakukan update field created_at dan updated_at secara otomatis
	public $timestamps = true;

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}

?>

