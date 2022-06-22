<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Ví dụ sử dụng Directive</title>
        <style>*{margin:0}body{padding:20px}</style>
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.2/angular.min.js"></script>
        <script>
            angular.module('myapp', [])
                    .controller('ExampleController', ['$scope', function($scope) {
                }]);
        </script>
    </head>
    <body ng-app="myapp">
        <div ng-controller="ExampleController">
            Name <input type="text" ng-model="name"><br>
            Website: <input type="text" ng-model="website"><br>
            <pre ng-bind-template="{{name + website}}!"></pre>
        </div>
    </body>
</html>