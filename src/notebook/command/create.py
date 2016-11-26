import datetime
import urllib
import re

from pyparsing import *


class NewNoteCommand:
    def __init__(self, workflow, github):
        self.wf = workflow
        self.gh = github

    def execute(self, query):
        try:
            parsed = NewNoteQuery(query)
        except:
            return False

        categories = self.wf.cached_data('categories', self.getCatergories, max_age=30)

        if parsed.category:
            hasExactMatch = False
            for c in categories:
                categoryName = c['name']
                if c['name'].lower() == parsed.category.lower():
                    hasExactMatch = True
            if not hasExactMatch:
                categories.insert(0, {'name': parsed.category, 'sha': 'newNoteIn' + categoryName})

        for category in categories:
            categoryName = category['name']
            if parsed.category is None or categoryName.lower().find(parsed.category.lower()) >= 0:
                itemUrl = self.getNewNoteUrl(parsed.name, parsed.category, parsed.tags)
                subtitle = 'Create new note in \'' + categoryName + '\'' if not parsed.name else 'Create note ' + categoryName + '/' + parsed.name
                autocompletion = re.sub('^\+', "+ #" + categoryName, query) if parsed.category is None else query.replace("#" + parsed.category, "#" + categoryName)

                self.wf.add_item(category['name'],
                                 subtitle,
                                 uid=category['sha'],
                                 arg=itemUrl,
                                 valid=True,
                                 autocomplete=autocompletion,
                                 icon='note-taking_icon.jpg')

        return True

    def getCatergories(self):
        return [i for i in self.gh.ls('/') if i['type'] == 'dir' and i['name'] != 'resources']

    def getNewNoteUrl(self, name, category, tags):
        categoryName = category if category is not None else ''
        template = self.getTemplate(tags)
        urlParams = {'value': template}
        if name:
            urlParams['filename'] = categoryName + "/" + name + ".md" if categoryName else name + ".md"
        return "https://github.com/" + self.gh.repo + "/new/master/" + categoryName + "?" + urllib.urlencode(urlParams)

    def getTemplate(self, tags = []):
            return "---\ncreated: " + datetime.date.today().strftime('%Y/%m/%d') + "\ntags: [" + ", ".join(tags) + "]\n---\n\n"

class NewNoteQuery:
    def __init__(self, query):
        self._parse(query)

    def _parse(self, query):
        tag = Combine(Suppress('@') + Word(alphanums))
        category = Combine(Suppress('#') + Word(alphanums))
        nameTerm = Word(printables)
        command = Suppress('+') + ZeroOrMore(Group(tag)('tag*') | Group(category)('category') | Group(nameTerm)('name*'))

        tokens = command.parseString(query)

        self.category = tokens.category[0] if tokens.category else None
        self.tags = [ t[0] for t in tokens.tag ]
        self.name = " ".join([ t[0] for t in tokens.name ])

    def __str__(self):
        return "{name: '%s', category: '%s', tags: [%s]}" % (self.name, self.category, " ,".join(self.tags))