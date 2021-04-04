<?php


namespace App\Entities;


class Message
{
    static $empty_values = "Verifique que haya diligenciado todos los campos.";
    static $success_register = "Datos almacenados exitosamente.";
    static $error_register = "Ha ocurrido un error al registrar la información, por favor intente nuevamente.";
    static $success_update = "Datos actualizados exitosamente.";
    static $success_search = "Busqueda Exitosa.";
    static $error_update = "Ha ocurrido un error al actualizar la información, por favor intente nuevamente.";
    static $success_delete = "Datos eliminados exitosamente.";
    static $error_delete = "Ha ocurrido un error al eliminar la información, por favor intente nuevamente.";
    static $error_query = "No se encuentran datos almacenados en el sistema.";
    static $empty_field = "Verifique que haya diligenciado el campo :attribute.";
    static $error_length_field = "Verifique que el campo :attribute no exceda los :max caracteres permitidos";
    static $error_length_field_min = "El campo :attribute debe tener :min o más caracteres";
    static $error_email = "Verifique que el :attribute diligenciado sea una dirección de correo válida";
    static $error_relation = "El {modelo}  no existe";
    static $error_exists_male = "El :attribute seleccionado no existe.";
    static $error_exists_female = "La :attribute seleccionada no existe.";
    static $error_exists_destroy_male = "El :attribute que desea eliminar no existe.";
    static $error_exists_destroy_female = "La :attribute que desea eliminar no existe.";
    static $error_unique_male = "El :attribute introducido ya existe.";
    static $error_unique_female = "La :attribute diligenciada ya existe.";
    static $error_date = "Verifique que el campo :attribute sea una fecha válida";
    static $error_field_array = 'El parametro que corresponde a <<<:attribute>>> debe ser un Array';
    static $error_field_string = 'El parametro que corresponde a <<<:attribute>>> debe ser un String';
    static $error_field_integer = 'El campo <<<:attribute>>> debe ser un Entero';
    static $error_field_positive_integer = 'El parametro que corresponde a <<<:attribute>>> debe ser un Entero Positivo';
    static $error_field_number_min = "Verifique que el campo <<<:attribute>>> tenga un valor no menor a :min";
    static $error_field_number_max = "Verifique que el campo <<<:attribute>>> etnga un valor no mayor a :max";
    static $internal_error = "Ha ocurrido un error inesperado, por favor intente nuevamente.";
    static $error_json = "Verifique que el campo :attribute tenga un formato json válido.";
    static $error_image_format = "Verifique el formato de la :attribute adjuntada. Solo están permitidos los formatos: jpg, jpeg, png y gif";
    static $error_max_file = "Verifique que el campo :attribute no exceda los :max MB permitidos.";
    static $error_unauthorized = "El usuario no está autorizado para realizar la solicitud.";
    static $invalid_credentials = "Credenciales inválidas.";
    static $error_user_not_exist = "El usuario que desea realizar la acción no existe o se encuentra inactivo. Por favor comuníquese con el administrador del sistema.";
}
