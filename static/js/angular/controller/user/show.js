app.controller('user.show', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$http.post('/api/user/show', {uid:$state.params.uid}).success(function (response) {
		if(response.code === '0000') {
			$scope.user = response.response;
			$scope.user.old_password = '';
			$scope.user.new_password = '';
		}
		else {
			// alert('No such user!');
		}
	});

	$scope.save = function () {
		var user = angular.copy($scope.user);
		if(user.new_password === '') user.new_password = user.old_password;
		user.new_password = md5(user.new_password);
		user.old_password = md5(user.old_password);
		// console.log(user);
		$http.post('/api/user/renew', user).success(function (response) {
			if(response.code === '0000') {
				alert('Done!');
			}
			else {
				alert(response.errorMsg);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);
