'use strict';

GitLog.GitTagList = React.createClass({
    getInitialState: function() {
        return {data: []};
    },
    load: function() {
        $.ajax({
            url: '/repos/' + this.props.owner + '/' + this.props.repo + '/tags',
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    componentDidMount: function() {
        this.load();
    },
    clickGitTag: function(name, sha, record) {
        this.props.handleSelect(name, sha, record);
    },
    render: function() {
        var me = this;
        var Panel = ReactBootstrap.Panel;
        var ListGroup = ReactBootstrap.ListGroup;
        var nodes = this.state.data.map(function (data) {
            var ListGroupItem = ReactBootstrap.ListGroupItem;
            var sha = data.commit.sha;
            var name = data.name;
            if (jQuery.isPlainObject(me.props.selectedGitTag) && me.props.selectedGitTag.commit.sha === sha) {
                return (
                    <ListGroupItem key={sha} id={sha} active onClick={me.clickGitTag.bind(me, name, sha, data)}>{name}</ListGroupItem>
                );
            }
            return (
                <ListGroupItem key={sha} id={sha} onClick={me.clickGitTag.bind(me, name, sha, data)}>{name}</ListGroupItem>
            );
        });
        return (
            <Panel header='Tags'>
                <ListGroup className="gitTagList" fill>
                    {nodes}
                </ListGroup>
            </Panel>
        );
    }
});
