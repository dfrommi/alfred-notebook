from workflow import web

class GitHub:
    def __init__(self, repo, token = None):
        self.repo = repo
        self.token = token

    def search(self, query):
        url = "https://api.github.com/search/code"
        fullQuery = query + " in:file,path"
        params= {'q': fullQuery + ' repo:' + self.repo}
        return self.getItems(url, params)['items']

    def ls(self, path = '/'):
        url = "https://api.github.com/repos/" + self.repo + "/contents" + path
        return self.getItems(url)

    def getUrlOfItem(self, item):
        return "https://github.com/" + self.repo + "/blob/master/" + item['path'].replace (" ", "%20")

    def getItems(self, url, params = {}):
        if self.token is not None:
            params['access_token'] = self.token
        return web.get(url, params).json()