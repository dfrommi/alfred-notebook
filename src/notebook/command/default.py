class DefaultCommand:
    def __init__(self, workflow, github):
        self.wf = workflow
        self.gh = github

    def execute(self, query):
        self.wf.add_item('Create new note',
                         self.gh.repo,
                         uid='searchRepo',
                         arg='https://github.com/' + self.gh.repo,
                         valid=False,
                         autocomplete='+',
                         icon='note-taking_icon.jpg')

        self.wf.add_item('Open notebook',
                         self.gh.repo,
                         uid='openRepo',
                         arg='https://github.com/' + self.gh.repo,
                         valid=True,
                         icon='book-icon.png')

        return True
