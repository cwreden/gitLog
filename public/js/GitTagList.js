'use strict';

GitLog.GitTagList = React.createClass({
    getInitialState: function() {
        return {data: []};
    },
    load: function() {
        $.ajax({
            url: '/repos/' + this.props.owner + '/' + this.props.repo + '/tags?page=' + this.page,
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
        this.page = 1;
        this.load();
    },
    clickGitTag: function(name, sha, record) {
        this.props.handleSelect(name, sha, record);
    },
    previousPage: function() {
        console.log('pre');
        if (this.page > 1) {
            this.page--;
            this.load();
        }
    },
    nextPage: function() {
        console.log('next');
        if (this.state.data.length === 30) {
            this.page++;
            this.load();
        }
    },
    render: function() {
        var me = this;
        var Panel = ReactBootstrap.Panel;
        var Pager = ReactBootstrap.Pager;
        var PageItem = ReactBootstrap.PageItem;
        var ListGroup = ReactBootstrap.ListGroup;
        var nodes = this.state.data.map(function (data, key) {
            var ListGroupItem = ReactBootstrap.ListGroupItem;
            var sha = data.commit.sha;
            var name = data.name;
            if (jQuery.isPlainObject(me.props.selectedGitTag) && me.props.selectedGitTag.commit.sha === sha) {
                return (
                    <ListGroupItem key={key} id={sha} active onClick={me.clickGitTag.bind(me, name, sha, data)}>
                        {name}
                    </ListGroupItem>
                );
            }
            return (
                <ListGroupItem key={key} id={sha} onClick={me.clickGitTag.bind(me, name, sha, data)}>{name}</ListGroupItem>
            );
        });

        var pager = '';
        if (nodes.length > 0) {
            var Previous = <PageItem previous disabled onSelect={this.previousPage}>&larr; Previous</PageItem>;
            if (this.page > 1) {
                Previous = <PageItem previous onSelect={this.previousPage}>&larr; Previous</PageItem>;
            }
            var Next = <PageItem next disabled onSelect={this.nextPage}>Next &rarr;</PageItem>;
            if (nodes.length === 30) {
                Next = <PageItem next onSelect={this.nextPage}>Next &rarr;</PageItem>;
            }
            pager = (
                <Pager>
                    {Previous}
                    {Next}
                </Pager>
            );
        }
        return (
            <Panel header='Tags'>
                {pager}
                <ListGroup className="gitTagList" fill>
                    {nodes}
                </ListGroup>
                {pager}
            </Panel>
        );
    }
});
