<?php 

namespace Csgt\Components;
use DB, Auth;

class Components {
  private static $monedas = array(
        array('country' => 'Guatemala', 'currency' => 'Q', 'singular' => 'QUETZAL', 'plural' => 'QUETZALES', 'symbol', 'Q'),
        array('country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'));

  private static function convertGroup($n) {
    $unidades = array('','UN ','DOS ','TRES ','CUATRO ','CINCO ','SEIS ','SIETE ','OCHO ','NUEVE ','DIEZ ',
    'ONCE ','DOCE ','TRECE ','CATORCE ','QUINCE ','DIECISEIS ','DIECISIETE ','DIECIOCHO ','DIECINUEVE ','VEINTE ');
    $decenas  = array('VENTI','TREINTA ','CUARENTA ','CINCUENTA ','SESENTA ','SETENTA ','OCHENTA ','NOVENTA ','CIEN ');
    $centenas = array('CIENTO ','DOSCIENTOS ','TRESCIENTOS ','CUATROCIENTOS ','QUINIENTOS ',
    'SEISCIENTOS ','SETECIENTOS ','OCHOCIENTOS ','NOVECIENTOS ');

    $output = '';
    if ($n == '100') 
      $output = "CIEN ";
    else if ($n[0] !== '0') 
      $output = $centenas[$n[0] - 1];   

    $k = intval(substr($n,1));

    if ($k <= 20) {
      $output .= $unidades[$k];
    } 
    else {
      if(($k > 30) && ($n[2] !== '0')) 
        $output .= sprintf('%sY %s', $decenas[intval($n[1]) - 2], $unidades[intval($n[2])]);
      else 
        $output .= sprintf('%s%s', $decenas[intval($n[1]) - 2], $unidades[intval($n[2])]);
    }
    return $output;
  }
  
	public static function getMenuForRole() {
		$usuarioroles = array();
		if(Config::get('components::multiplesroles')) {
			$usuarioroles = DB::table('authusuarioroles')
				->where('usuarioid', Auth::id())
				->lists('rolid');
		}

		else
			$usuarioroles[] = Auth::user()->rolid;

		$query = 'SELECT * FROM authmenu WHERE ruta IN (
			SELECT 
				CONCAT(IF(m.nombre<>\'index\',m.nombre,\'/\'), IF(p.nombre<>\'index\',CONCAT(\'/\',p.nombre),\'\')) AS ruta 
			FROM
				authrolmodulopermisos rmp
				LEFT JOIN authmodulopermisos mp ON (mp.modulopermisoid=rmp.modulopermisoid)
				LEFT JOIN authmodulos m ON (m.moduloid=mp.moduloid)
				LEFT JOIN authpermisos p ON (p.permisoid=mp.permisoid)
			WHERE
				rmp.rolid IN(' . $usuarioroles . ')
			)
			OR menuid IN (
			SELECT padreid FROM authmenu WHERE ruta IN (
			SELECT 
			 CONCAT(IF(m.nombre<>\'index\',m.nombre,\'/\'), IF(p.nombre<>\'index\',CONCAT(\'/\',p.nombre),\'\')) AS ruta 
			FROM
				authrolmodulopermisos rmp
				LEFT JOIN authmodulopermisos mp ON (mp.modulopermisoid=rmp.modulopermisoid)
				LEFT JOIN authmodulos m ON (m.moduloid=mp.moduloid)
				LEFT JOIN authpermisos p ON (p.permisoid=mp.permisoid)
			WHERE
				rmp.rolid IN(' . $usuarioroles . ')
			) AND padreid IS NOT NULL
			) ORDER BY padreid, orden';
		return DB::select(DB::raw($query));
	}

	public static function numeroALetras($aNumero, $aMoneda=null, $aDecimales=0) {
    $aNumero = str_replace(',', '', $aNumero); //Quitar las comas  
    $enteroDecimal = explode('.', $aNumero);
    $aNumero = $enteroDecimal[0];

    if ($aMoneda !== null) {
      try {
        $moneda = array_filter(self::$monedas, function($m) use ($aMoneda) {
        	return ($m['currency'] == $aMoneda);
        });

        $moneda = array_values($moneda);

        if (count($moneda) <= 0) {
          throw new Exception("Tipo de moneda inválido");
          return;
        }

        if ($aNumero < 2) {
        	$moneda = $moneda[0]['singular'];
        } else {
          $moneda = $moneda[0]['plural'];
        }
      } catch (Exception $e) {
        echo $e->getMessage();
        return;
      }
    } else {
      $moneda = " ";
    }

    $converted = '';

    if (($aNumero < 0) || ($aNumero > 999999999)) {
      return 'No es posible convertir el numero a letras';
    }

		$aNumeroStr     = (string) $aNumero;
		$aNumeroStrFill = str_pad($aNumeroStr, 9, '0', STR_PAD_LEFT);
		$millones       = substr($aNumeroStrFill, 0, 3);
		$miles          = substr($aNumeroStrFill, 3, 3);
		$cientos        = substr($aNumeroStrFill, 6);

    if (intval($millones) > 0) {
      if ($millones == '001') 
        $converted .= 'UN MILLON ';
      else if (intval($millones) > 0) 
        $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
    }
    
    if (intval($miles) > 0) {
      if ($miles == '001') 
        $converted .= 'MIL ';
      else if (intval($miles) > 0)
        $converted .= sprintf('%sMIL ', self::convertGroup($miles));
    }

    if (intval($cientos) > 0) {
      if ($cientos == '001') 
        $converted .= 'UN ';
      else if (intval($cientos) > 0) 
        $converted .= sprintf('%s ', self::convertGroup($cientos));
    }
    if (count($enteroDecimal)>1) {
      $enteroDecimal[1] = substr($enteroDecimal[1],0,$aDecimales);
      if ($enteroDecimal[1]<>'')
        $converted .= ' PUNTO ' . self::numeroALetras($enteroDecimal[1]) . ' ';
    }

    $converted .= $moneda;
    return trim($converted);
  }

	public static function fechaHumanoAMysql($aFecha) {
		
		$fh = explode(' ', $aFecha);
		if (sizeof($fh)==2) 
			$laFecha = $fh[0];
		else
			$laFecha = $aFecha;

		$partes = explode('/', $laFecha);
		if (sizeof($partes)==1)
			$partes = explode('-', $laFecha);

		return $partes[2] . '-' . $partes[1] . '-' . $partes[0] . ((sizeof($fh)==2)?' ' . $fh[1]:'');
	}

	public static function fechaMysqlAHumano($aFecha) {

		$fh = explode(' ', $aFecha);
		if (sizeof($fh)==2)
			$laFecha = $fh[0];
		else
			$laFecha = $aFecha;

		$partes = explode('/', $laFecha);
		if (sizeof($partes)==1)
			$partes = explode('-', $laFecha);

		return $partes[2] . '-' . $partes[1] . '-' . $partes[0] . ((sizeof($fh)==2)?' ' . $fh[1]:'');
	}
}