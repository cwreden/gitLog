'use strict';

if (!jQuery.isPlainObject(GitLog)) {var GitLog = {};}

GitLog.Application = React.createClass({
    render: function() {
        var NavigationBar = GitLog.NavigationBar;
        var RouteHandler = ReactRouter.RouteHandler;
        return (
            <div>
                <NavigationBar/>
                <div id="content">
                    <RouteHandler/>
                </div>
            </div>
        );
    }
});
