app.controller('signin', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$scope.user = {
		username: '',
		password: ''
	};
	$scope.signin = function() {
		var user = angular.copy($scope.user);
		user.password = md5(user.password);
		// console.log(user);
		$http.post('/api/user/signin', user).success(function (response) {
			if(response['code'] === '0000') {
				$rootScope.$broadcast('sessionData:willRefresh');
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
