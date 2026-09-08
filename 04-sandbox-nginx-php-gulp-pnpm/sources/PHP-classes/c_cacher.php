<?php
/**
 * c_cacher.php
 * User: Peter Acosta
 * Date: Domingo 13 de Mayo 2018
 * Maneja cache en el disco
 */


class c_cacher
{


    /** ** ******************************************************************************************************************************* 1
     * @example start_ob_cache()
     * @since Domingo 20 de Mayo de 2018
     * @author Peter Acosta
     * Inicia el Output buffer cache
     */
    public static function start_ob_cache()
    {
        if (CECO_CACHE_ON) ob_start();
    }


    /** ** ******************************************************************************************************************************* 2
     * @example end_ob_cache($token)
     * @since Domingo 20 de Mayo de 2018
     * @author Peter Acosta
     * Finaliza el Output buffer cache previamente iniciado, guarda el contenido en un archivo, y envia lo mismo al Output
     * @param string $token
     */
    public static function end_ob_cache($token)
    {
        if (CECO_CACHE_ON) {
            file_put_contents(
                CECO_CACHE_FOLDER . 'ob-cache--' . $token . '.inc',
                PHP_EOL . '<!-- Cached ' . date('Y-m-d H:i:s') . ' -->' . PHP_EOL . ob_get_contents() . PHP_EOL . '<!-- cached -->' . PHP_EOL);
            ob_end_flush();
        }
    }


    /** ** ******************************************************************************************************************************* 3
     * @example include_cached($token)
     * @since Domingo 20 de Mayo de 2018
     * @author Peter Acosta
     * Hace un include de lo previamente guardado con end_ob_cache()
     * @param string $token
     * @return bool $included
     */
    public static function include_cached($token)
    {
        if (CECO_CACHE_ON) {
            $file_name = CECO_CACHE_FOLDER . 'ob-cache--' . $token . '.inc';
            if (file_exists($file_name)) {
                $included = include($file_name);
            } else {
                $included = false;
            }
        } else {
            $included = false;
        }
        return $included;
    }


    /** ** ******************************************************************************************************************************* 4
     * @example c_cacher::array_save($token, $my_array)
     * @param string $token
     * @param array $my_array
     * Guarda un array en la carpeta de cache , listo para ser incluido mas tarde
     */
    public static function array_save($token, $my_array)
    {
        if (CECO_CACHE_ON) {
            $file_name = self::array_file_name($token);
            $content = '<?php' . PHP_EOL . '/*---- Cached ' . date('Y-m-d H:i:s') . ' ----*/' . PHP_EOL;  // OJO DEJAR ASI
            $content .= ' return ' . var_export($my_array, true) . ';';
            file_put_contents($file_name, $content);
        }
    }

    /** ** ******************************************************************************************************************************* 5
     * @example c_cacher::array_load($token)
     * @param string $token
     * @return array
     * Devuleve un array previamente guardado con save_array
     */
    public static function array_load($token)
    {
        if (CECO_CACHE_ON) {
            $file_name = self::array_file_name($token);
            if (file_exists($file_name)) {
                return include($file_name);
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /** ** ******************************************************************************************************************************* 6
     * @example c_cacher::array_file_name($token)
     * Arma el nombre del archivo donde se van a guardar los arrays cacheados
     * @param string $token
     * @return string array_name
     */
    public static function array_file_name($token)
    {
        return CECO_CACHE_FOLDER . 'array--' . $token . '.inc';
    }

}