app.controller('signin', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$scope.user = {
		username: '',
		password: ''
	};
	$scope.login = function() {
		var user = angular.copy($scope.user);
		user.password = md5(user.password);
		$http.post('/api/user/signin', user).success(function (response) {
			if(response['code'] === '0000') {
				$state.go('index');
			}
			else {
				alert('账户与密码不匹配，请重新输入！');
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);
