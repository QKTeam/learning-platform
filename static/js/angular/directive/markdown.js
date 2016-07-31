app.directive('markdown', function() {
	return {
		restrict: 'EA',
		scope: {
			content: '='
		},
		link: function($scope, $element) {
			if (angular.isDefined($scope.content)) {
				return $scope.$watch('content', function() {
					var content = angular.copy($scope.content);
					// marked.setOptions({breaks:true});
					content = marked(content);
					content = content.replace(/<table/g, '<table class="table table-bordered"');
					$element.empty().append(content);
					MathJax.Hub.Queue(["Typeset", MathJax.Hub, $element[0]]);
				}, true);
			}
		},
		template: '<div></div>'
	};
});
