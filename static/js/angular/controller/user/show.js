app.controller('user.show', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$http.post('/api/user/show', {uid:$state.params.uid}).success(function (response) {
		if(response.code === '0000') {
			$scope.user = response.response;
		}
		else {
			// alert('No such user!');
		}
	});
}]);
