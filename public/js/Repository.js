'use strict';

GitLog.Repository = React.createClass({
    componentDidMount: function () {
        console.log(this.props.params.owner, this.props.params.repo);
        // TODO load user data
    },
    render: function () {
        return (
            <div className="repository">
                <h2>{this.props.params.owner}/{this.props.params.repo}</h2>
            </div>
        );
    }
});
