
angular.module('Rates', [])
    .controller('DefaultController', function($scope, $http) {

        $scope.rates = {
            visible : [],
            invisible : [],
            reset : function() {
                this.visible = [];
                this.invisible = [];
            }
        };

        $scope.getRates = function() {
            $http.get('/application/rate/get').success(
                function(data) {
                    $scope.rates.reset();
                    for (var i in data) {
                        if (parseInt(data[i].visible) === 1) {
                            $scope.rates.visible.push(data[i])
                        } else {
                            $scope.rates.invisible.push(data[i])
                        }
                    }
                }
            );
        };

        $scope.hide = function(rate) {

            $http.post('/application/rate/hide',
                {
                    currency_id : rate.currency_id
                }
            ).success(function() {
                    $scope.getRates();
                });

        };

        $scope.show = function(rate) {

            $http.post('/application/rate/show',
                {
                    currency_id : rate.currency_id
                }
            ).success(function() {
                    $scope.getRates();
                });

        };

        $scope.getTimeToEndOfDay = function() {
            $http.get('/application/index/timeToEndOfDay').success(
                function(data) {
                    setTimeout(
                        $scope.getRates,
                        data
                    );
                }
            );
        };
    });
