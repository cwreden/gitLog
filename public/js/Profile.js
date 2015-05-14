'use strict';

GitLog.Profile = React.createClass({
    componentDidMount: function () {
        console.log(this.props.params.owner);
        // TODO load user data
    },
    render: function () {
        var RepositoryList = GitLog.RepositoryList;
        return (
            <div className="profile">
                <h2>{this.props.params.owner}</h2>
                <RepositoryList owner={this.props.params.owner}/>
            </div>
        );
    }
});
