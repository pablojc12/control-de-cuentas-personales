/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* global acount */

acount.service('http', ['$http', '$q', function($http, $q){
        this.json = {};
        
        this.action = function()
        {
            var defered = $q.defer();
            var promise = defered.promise;
            
            $http({method: 'POST',
                url: 'server/server.php',
                data: this.json,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response)
            {
                defered.resolve(response);
            }, 
            function errorCallback(response)
            {
                defered.reject(response);
            });
            
            return promise;
        };
}]);