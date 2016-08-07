app.controller('index', ['$scope', '$rootScope', '$http', '$timeout', '$state', function($scope, $rootScope, $http, $timeout, $state){
	$state.go('course.search');
}]);
