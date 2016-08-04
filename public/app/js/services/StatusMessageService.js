'use strict';

/**
 * @ngdoc service
 * @name Monitor.StatusMessage
 * @description
 * # StatusMessage
 * Service in the planillasApp.
 */
angular.module('Monitor')
    .service('StatusMessageService', function () {

        function showResolve() {

        }

        /**
         *
         * @param data_reject
         * @param options
         *    hide: true|false
         */
        function showReject(data_reject, options) {
            var status = data_reject.status;
            var message = data_reject.data && data_reject.data.detail;
            if(options && options.hideMessage){
                return;
            }
            if (message) {
                toastr.warning(message);
            }else{
                switch (status){
                    case 400:toastr.warning('Sintaxis errónea');break;
                    case 401:toastr.warning('Sin autorizacion');break;
                    case 402:toastr.warning('');break;
                    case 403:toastr.warning('No tiene permisos');break;
                    case 404:toastr.warning('Recurso no encontrado');break;
                    case 405:toastr.warning('Método no encontrado');break;
                    case 406:toastr.warning('Métodos de respuesta no soportados');break;
                    case 407:toastr.warning('Error en proxy');break;
                    case 408:toastr.warning('Tiempo limite excedido');break;
                    case 409:toastr.warning('Error al procesar peticion');break;
                    case 410:toastr.warning('Recurso ya no esta disponeble');break;

                    case 500:toastr.error('Error interno del servidor');break;
                    case 501:toastr.error('Metodo no encontrado');break;
                    case 502:toastr.error('Error en servidor');break;
                    case 503:toastr.error('Servidor en mantenimiento');break;
                    case 504:toastr.error('Tiempo de respuesta excedido');break;
                    case 505:toastr.error('Protocolo HTTP no soportado');break;
                    case 506:toastr.error('Referencia circular');break;
                    case 507:toastr.error('Espacio de almacenamiento insuficiente');break;
                    case 508:toastr.error('Se ha detectado un bucle infinito');break;
                    case 509:toastr.error('Limite de ancho de banda excedido');break;
                    case 510:toastr.error('Metodo no extendido');break;
                    case 511:toastr.error('Autenticacion requerida');break;
                }
            }
        }

        return {
            showResolve: showResolve,
            showReject: showReject
        };
    });
