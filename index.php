<?php
    function importWebpackModule($moduleNames) {
        $scriptsMapper = function($moduleName) {
            return '<script>' . file_get_contents('dist/'.$moduleName.'.js') . '</script>';
        };

        echo join ('', array_map($scriptsMapper, $moduleNames));

        $windowVariableMapper = function($moduleName) {
            return 'window[\'' . $moduleName . '\'] = __webpack_require__(\'./src/' . $moduleName . '.js\');';
        };

        $windowVariableSet = join ('', array_map($windowVariableMapper, $moduleNames));
        ?>
        <script>
            (function() {
                var modules = {};
                var installedModules = {};
                var installedChunks = {};
                function webpackJsonpCallback(data) {
            		var chunkIds = data[0];
            		var moreModules = data[1];


            		// add "moreModules" to the modules object,
            		// then flag all "chunkIds" as loaded and fire callback
            		var moduleId, chunkId, i = 0, resolves = [];
            		for(;i < chunkIds.length; i++) {
            			chunkId = chunkIds[i];
            			if(installedChunks[chunkId]) {
            				resolves.push(installedChunks[chunkId][0]);
            			}
            			installedChunks[chunkId] = 0;
            		}
            		for(moduleId in moreModules) {
            			if(Object.prototype.hasOwnProperty.call(moreModules, moduleId)) {
            				modules[moduleId] = moreModules[moduleId];
            			}
            		}
            		if(parentJsonpFunction) parentJsonpFunction(data);
                
            		while(resolves.length) {
            			resolves.shift()();
            		}
                
                };

                __webpack_require__.r = function(exports) {
                	if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
                		Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
                	}
                	Object.defineProperty(exports, '__esModule', { value: true });
                };
                
                function __webpack_require__(moduleId) {
                	// Check if module is in cache
                	if(installedModules[moduleId]) {
                		return installedModules[moduleId].exports;
                	}
                	// Create a new module (and put it into the cache)
                	var module = installedModules[moduleId] = {
                		i: moduleId,
                		l: false,
                		exports: {}
                	};
                
                	// Execute the module function
                	modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
                
                	// Flag the module as loaded
                	module.l = true;
                
                	// Return the exports of the module
                	return module.exports;
                }

                var jsonpArray = window["webpackJsonp"] = window["webpackJsonp"] || [];
                var oldJsonpFunction = jsonpArray.push.bind(jsonpArray);
                jsonpArray.push = webpackJsonpCallback;
                jsonpArray = jsonpArray.slice();
                for(var i = 0; i < jsonpArray.length; i++) webpackJsonpCallback(jsonpArray[i]);
                var parentJsonpFunction = oldJsonpFunction;

                <?php echo $windowVariableSet; ?>
            })();
            
        </script>
        <?php
    }
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php importWebpackModule(['module1']); ?>
        <!-- <?php importWebpackModule(['module2']); ?> -->
        <script>
            console.log('module1  => ', window.module1 && module1.default);
            console.log('module2  => ', window.module2 && module2.default);
        </script>
    </body>
</html>