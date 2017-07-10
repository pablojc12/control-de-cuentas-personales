/* global acount */

acount.controller('ctrlApp', ['$scope', 'http', function($scope, http){
        /**
         * Ventana modal de alertas 
         *      Lanzamiento:
         *          $('#alertModal').modal('show');
         */
        $scope.alertModal = 'container/modals/alert.html';
        $scope.titleModal = ''; // Titulo del modal
        $scope.bodyModal = ''; // Contenido del modal
        
        $scope.modalLoader = 'container/modals/modalLoader.html';
        
        /**
         * Ventana modal con acciones de boton cancel and submit
         *      Lanzamiento:
         *          $('#modalAction').modal('show');
         */
        $scope.modalAction = 'container/modals/modalAction.html';
        $scope.titleModalAction = ''; // Titulo del modal
        $scope.bodyModalAction = ''; // Conetido del modal
        $scope.valueFunction = ''; // objeto json
        $scope.modalActionSubmit = function (valueFunction)
        {
            if(typeof valueFunction === 'object')
            {
                if (typeof valueFunction.getFunction === 'function')
                    valueFunction.getFunction();
                else
                {
                    $scope.titleModal = 'Function no enctontrada';
                    $scope.bodyModal = 'Para que la funcion sea lanzada, tiene que mandar un objeto JSON {getFunction: function(){-- contenido de la funcion -- }}';
                    $('#alertModal').modal('show');
                }
            }
        };
        
        $scope.modalUpdateUser = 'container/modals/modalUpdateUser.html'; // Template html de actualizar usuario
        $scope.idUserForNewAcount = '';
        $scope.modalNewAcount = 'container/modals/newAcount.html'; // Template html de newAcount
        $scope.showModalNewAcount = function (id){$('#modalNewAcount').modal('show'); $scope.idUserForNewAcount = id;};
        $scope.newUserContainer = 'container/newUser/newUser.html'; //Template html de newUser
        $scope.searchContainer = 'container/searchContainer/searchContainer.html'; //Template html de search
        $scope.valueSearch = '';
        
        $scope.orderBy = '';
        $scope.users = []; // Vaciado de toda la informacion dentro de la base de datos
        
        $scope.acounts = []; //vaciado de la base de datos
        $scope.acountProv = []; //accion push o splice en las cuentas
        $scope.resetAcountProv = function(){$scope.acountProv = []; $scope.alertAcountEmpty = false;};
        $scope.alertAcountEmpty = false;
        $scope.resetNewUser = function()
        {
            $scope.newUser = {
                email: '',
                name: '',
                lastName: '',
                phone: '',
                acounts: []
            };
        };
        
        /**
         * New User
         */
        $scope.newUser = {
            email: '',
            name: '', 
            lastName: '',
            phone: '',
            acounts: $scope.acountProv
        };
        
        /**
         * New acount
         */
        $scope.newAcount = {
            acount: 'off', 
            user: '', 
            pass: ''
        };
        
        /**
         * Agregacion dentro de un array el objeto json que contiene 
         * los datos de la cuenta: usuario y contraseña
         */
        $scope.pushAcount = function()
        {
            $scope.acountProv.push($scope.newAcount);
            $scope.newAcount = {acount: 'off', user: '', pass: ''};
        };
        
        /**
         * Eliminacion del objeto del arreglo que contiene las 
         * cuentas con los usuarios y contraseñas
         * @param {type} index
         */
        $scope.removeAcount = function(index)
        {
            $scope.acountProv.splice(index, 1);
            $scope.acountProv.sort($scope.acountProv);
        };
        
        /**
         * Extraccion de los valores de cuentas 
         */
        $scope.remakeAcount = function()
        {
            http.json = {
                class: 'Acount',
                method: 'remake'
            };
            
            http.action().then(function(response)
            {
                $scope.acounts = response.data;
                $scope.chargeToltip();
            }).catch(function(response)
            {
                console.log(response);
            });
        };
        
        /**
         * Almacenado del usuario dentro de la base de datos
         */
        $scope.saveUser = function()
        {
            $('#modalNewUser').modal('show');
            
            $scope.valueFunction = {
                getFunction: function () 
                {
                    $('#modalLoaderP').modal('show');
                    // Carga del objeto json para realizar las peticiones
                    http.json = {
                        class: 'User',
                        method: 'newUser',
                        data: $scope.newUser
                    };
                    http.action().then(function (response)
                    {
                        $('#modalLoaderP').modal('hide');
                        if (response.data.status)
                        {
                            $('#modalNewUser').modal('hide');
                            $scope.titleModal = 'Correcto!';
                            $scope.bodyModal = 'El usuario se agrego con éxito a la base de datos';
                            $('#alertModal').modal('show');
                            $scope.remakeUser();
                            $scope.resetNewUser();
                        } else
                        {
                            $('#modalNewUser').modal('hide');
                            $scope.titleModal = 'Oups..! Algo salio mal';
                            $scope.bodyModal = 'Puede que el correo que ingreso ya se encuentre dado de alta en el sistema';
                            $('#alertModal').modal('show');
                        }
                    }).catch(function (response)
                    {
                        console.log(response);
                    });
                }
            };
        };
        
        /**
         * Agregacion de cuentas al usuario
         * @param {string} id 
         */
        $scope.addNewAcount = function(id)
        {
            if($scope.acountProv.length === 0)
                $scope.alertAcountEmpty = true; //alerta de llenado de array
            else
            {
                $scope.alertAcountEmpty = false; //alerta de llenado de array
                
                $('#modalLoaderP').modal('show');
                
                http.json = {
                    class: 'Detail',
                    method: 'addDetail',
                    email: id,
                    acounts: $scope.acountProv
                };

                http.action().then(function(response)
                {
                    $('#modalLoaderP').modal('hide');
                    if(response.data.status)
                    {
                        $('#modalNewAcount').modal('hide');
                        $scope.titleModal = 'Correcto!';
                        $scope.bodyModal = 'Se agregaron las cuentas con éxito al usuario --' + id + '--';
                        $('#alertModal').modal('show');
                        $scope.resetAcountProv();
                        $scope.remakeUser();
                    }
                    else
                    {
                        $('#modalNewAcount').modal('hide');
                        $scope.titleModal = 'Oups..! Algo salio mal';
                        $scope.bodyModal = 'Puede que ya no exista la cuenta, revise el usuario';
                        $('#alertModal').modal('show');
                    }
                }).catch(function(response){
                    console.log(response);
                });
            }
        };
        
        /**
         * Eliminacion de una cuenta de usuario
         * @param {JSON} obj
         */
        $scope.deleteDetail = function(obj)
        {
            $scope.titleModalAction = 'Eliminacion de cuenta ' + obj.acount;
            $scope.bodyModalAction = 'Usuario: ' + obj.user + ' -- Contraseña: ' + obj.pass;
            
            $scope.valueFunction = {
                getFunction: function()
                {
                    $('#modalLoaderP').modal('show');
                    http.json = {
                        class: 'Detail',
                        method: 'deleteDetail',
                        id: obj.id
                    };
                    
                    http.action().then(function(response)
                    {
                        $('#modalLoaderP').modal('hide');
                        if(response.data.status)
                        {
                            $('#modalAction').modal('hide');
                            $scope.titleModal = 'Correcto!';
                            $scope.bodyModal = 'Se elimino la cuenta ' + obj.acount + ' con éxito';
                            $('#alertModal').modal('show');
                            $scope.remakeUser();
                        }
                        else
                        {
                            $('#modalAction').modal('hide');
                            $scope.titleModal = 'Oups..! Algo salio mal';
                            $scope.bodyModal = 'Puede que ya no exista la cuenta, revise el usuario';
                            $('#alertModal').modal('show');
                        }
                    }).catch(function(response){
                        console.log(response);
                    });
                }
            };
            
            $('#modalAction').modal('show');
        };
        
        /**
         * Eliminacion del usuario
         * @param {String} id 
         */
        $scope.deleteUser = function (id)
        {
            $scope.titleModalAction = 'Eliminar usuario ' + id;
            $scope.bodyModalAction = 'Para que la eliminación de lleve a cabo, le pedimos que elimine todas las cuentas vinculadas a este usuario ';
            $('#modalAction').modal('show');
            
            $scope.valueFunction = {
                getFunction: function()
                {
                    $('#modalLoaderP').modal('show');
                    http.json = {
                        class: 'User',
                        method: 'deleteUser',
                        email: id
                    };
                    
                    http.action().then(function(response)
                    {
                        $('#modalLoaderP').modal('hide');
                        if(response.data.status)
                        {
                            $('#modalAction').modal('hide');
                            $scope.titleModal = 'Correcto!';
                            $scope.bodyModal = 'Se elimino el usuario ' + id + ' con éxito';
                            $('#alertModal').modal('show');
                            $scope.remakeUser();
                        }
                        else
                        {
                            $('#modalAction').modal('hide');
                            $scope.titleModal = 'Oups..! Algo salio mal';
                            $scope.bodyModal = 'Puede que ya no exista la cuenta o tiene cuentas vinculadas a este usuario, revise al usuario y si tiene cuentas vinculadas solamente eliminelas';
                            $('#alertModal').modal('show');
                        }
                    }).catch(function(response){
                        
                    });
                }
            };
        };
        
        /**
         * Actualizacion de usuario
         * @param {JSON} obj
         */
        $scope.updateUser = function(obj)
        {
            $scope.resetNewUser();
            
            $scope.newUser.email = obj.email;
            $scope.newUser.name = obj.name;
            $scope.newUser.lastName = obj.lastName;
            $scope.newUser.phone = obj.phone;
            
            $('#modalUpdateUser').modal('show');
            
            $scope.valueFunction = {
                getFunction: function()
                {

                    $('#modalLoaderP').modal('show');
                    http.json = {
                        class: 'User',
                        method: 'updateUser',
                        data: $scope.newUser
                    };
                    
                    http.action().then(function(response)
                    {
                        $('#modalLoaderP').modal('hide');
                        if(response.data.status)
                        {
                            $('#modalUpdateUser').modal('hide');
                            $scope.titleModal = 'Correcto!';
                            $scope.bodyModal = 'Se actualizo el usuario ' + obj.email + ' con éxito';
                            $('#alertModal').modal('show');
                            $scope.remakeUser();
                            $scope.resetNewUser();
                            
                        }else{
                            $('#modalUpdateUser').modal('hide');
                            $scope.titleModal = 'Oups..! Algo salio mal';
                            $scope.bodyModal = 'Puede que ya no exista la cuenta, revise el usuario';
                            $('#alertModal').modal('show');
                        }
                    }).catch(function(response){
                        console.log(response);
                    });
                }
            };
        };
        
        /**
         * Extraccion de todos los datos de usuarios para la 
         * visualizacion dentro del HTML
         */
        $scope.remakeUser = function()
        {
            /**
             * Carga del json dentro del servicio http
             */
            http.json = {
                class: 'User',
                method: 'remake'
            };
            
            /**
             * Respuesta del servidor
             * @param {object} response 
             */
            http.action().then(function(response)
            {
                $scope.users = response.data;
                $scope.chargeToltip();
            }).catch (function(response)
            {
                console.log(response);
            }); 
        };
        
        $scope.chargeToltip = function()
        {
          $('[data-toggle="tooltip"]').tooltip();  
        };
}]);