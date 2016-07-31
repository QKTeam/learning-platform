app.controller('signup', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$scope.user = {
		username: '',
		password: '',
		email: '',
		phone: '',
		gender: '0',
		studentID: ''
	};
	$scope.errorMsg = '';
	$scope.signin = function() {
		var user = angular.copy($scope.user);
		user.password = md5(user.password);
		// console.log(user);
		$http.post('/api/user/signup', user).success(function (response) {
			// console.log(response);
			if(response.code === '0000') {
				alert('注册成功');
				$state.go('signin');
			}
			else {
				$scope.errorMsg = response.errorMsg;
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);
