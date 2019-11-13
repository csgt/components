<?php
namespace Csgt\Components;

use DB;
use Auth;
use Exception;
use SoapClient;
use Carbon\Carbon;

class Components
{
    private static $monedas = [
        ['country' => 'Guatemala', 'currency' => 'Q', 'singular' => 'QUETZAL', 'plural' => 'QUETZALES', 'symbol', 'Q'],
        ['country' => 'Guatemala', 'currency' => 'GTQ', 'singular' => 'QUETZAL', 'plural' => 'QUETZALES', 'symbol', 'GTQ'],
        ['country' => 'Estados Unidos', 'currency' => 'USD', 'singular' => 'DÓLAR', 'plural' => 'DÓLARES', 'symbol', 'US$'],
    ];

    private static function convertGroup($n, $aEscribirCeros = false)
    {
        $unidades = ['', 'UNO ', 'DOS ', 'TRES ', 'CUATRO ', 'CINCO ', 'SEIS ', 'SIETE ', 'OCHO ', 'NUEVE ', 'DIEZ ',
            'ONCE ', 'DOCE ', 'TRECE ', 'CATORCE ', 'QUINCE ', 'DIECISEIS ', 'DIECISIETE ', 'DIECIOCHO ', 'DIECINUEVE ', 'VEINTE '];
        $decenas  = ['VENTI', 'TREINTA ', 'CUARENTA ', 'CINCUENTA ', 'SESENTA ', 'SETENTA ', 'OCHENTA ', 'NOVENTA ', 'CIEN '];
        $centenas = ['CIENTO ', 'DOSCIENTOS ', 'TRESCIENTOS ', 'CUATROCIENTOS ', 'QUINIENTOS ',
            'SEISCIENTOS ', 'SETECIENTOS ', 'OCHOCIENTOS ', 'NOVECIENTOS '];

        $output = '';

        if ($aEscribirCeros) {
            for ($d = 0; $d < strlen($n); $d++) {
                if ($n[$d] == 0) {
                    $output .= 'CERO ';
                } else {
                    break;
                }

            }
        }
        $n = str_pad($n, 3, '0', STR_PAD_LEFT);

        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = $centenas[$n[0] - 1];
        }

        $k = intval(substr($n, 1));

        if ($k <= 20) {
            $output .= $unidades[$k];
        } else {
            if (($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', $decenas[intval($n[1]) - 2], $unidades[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', $decenas[intval($n[1]) - 2], $unidades[intval($n[2])]);
            }

        }

        return $output;
    }

    public static function numeroALetras($aNumero, $aMoneda = null, $aDecimales = 0, $aEscribirCeros = false)
    {
        $aNumero       = str_replace(',', '', $aNumero); //Quitar las comas
        $enteroDecimal = explode('.', $aNumero);
        $aNumero       = $enteroDecimal[0];

        if ($aMoneda !== null) {
            try {
                $moneda = array_filter(self::$monedas, function ($m) use ($aMoneda) {
                    return ($m['currency'] == $aMoneda);
                });

                $moneda = array_values($moneda);

                if (count($moneda) <= 0) {
                    throw new \Exception("Tipo de moneda inválido");

                    return;
                }

                if ($aNumero < 2) {
                    $moneda = $moneda[0]['singular'];
                } else {
                    $moneda = $moneda[0]['plural'];
                }
            } catch (\Exception $e) {
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

        if ($aEscribirCeros) {
            $cientos = $aNumero;
        } else {
            $cientos = substr($aNumeroStrFill, 6);
        }

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }

        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }

        }

        //Cientos
        if (intval($cientos) > 0) {
            if (($cientos == '001') && ($aEscribirCeros == false)) {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos, $aEscribirCeros));
            }

        }

        //Decimales
        if (count($enteroDecimal) > 1) {
            $enteroDecimal[1] = substr($enteroDecimal[1], 0, $aDecimales);
            if (intval($enteroDecimal[1]) != 0) {
                $converted .= ' PUNTO ' . self::numeroALetras($enteroDecimal[1], null, 0, true) . ' ';
            }
        }

        $converted .= $moneda;

        return trim($converted);
    }

    public static function getMenuForRole()
    {
        $usuarioroles = [];
        if (config('csgtcancerbero.multiplesroles')) {
            $usuarioroles = DB::table('authusuarioroles')
                ->where('usuarioid', Auth::id())
                ->lists('rolid');
        } else {
            $usuarioroles[] = Auth::user()->rolid;
        }

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

    public static function fechaHumanoAMysql($aFecha, $aSeparador = '/')
    {
        $fh = explode(' ', $aFecha);
        if (sizeof($fh) == 2) {
            $formato    = 'd' . $aSeparador . 'm' . $aSeparador . 'Y H:i';
            $formatoOut = 'Y-m-d H:i';
            $aFecha     = substr($aFecha, 0, 16);
        } else {
            $formato    = 'd' . $aSeparador . 'm' . $aSeparador . 'Y';
            $formatoOut = 'Y-m-d';
        }

        try {
            $fecha = Carbon::createFromFormat($formato, $aFecha);

            return $fecha->format($formatoOut);
        } catch (Exception $e) {
            return '0000-00-00 00:00';
        }
    }

    public static function fechaMysqlAHumano($aFecha, $aSeparador = '/')
    {
        $fh = explode(' ', $aFecha);
        if (sizeof($fh) == 2) {
            $formatoOut = 'd' . $aSeparador . 'm' . $aSeparador . 'Y H:i';
            $formato    = 'Y-m-d H:i';
            $aFecha     = substr($aFecha, 0, 16);
        } else {
            $formatoOut = 'd' . $aSeparador . 'm' . $aSeparador . 'Y';
            $formato    = 'Y-m-d';
        }

        try {
            $fecha = Carbon::createFromFormat($formato, $aFecha);

            return $fecha->format($formatoOut);
        } catch (Exception $e) {
            return '00-00-0000 00:00';
        }
    }

    public static function getTipoCambio()
    {
        try {
            $soapClient = new SoapClient("http://www.banguat.gob.gt/variables/ws/TipoCambio.asmx?wsdl", ["trace" => 1]);
            $info       = $soapClient->__call("TipoCambioDia", []);

            return $info->TipoCambioDiaResult->CambioDolar->VarDolar->referencia;
        } catch (Exception $e) {
            return 0;
        }
    }
}
