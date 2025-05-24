import json
import os

class NestedJsonFile:
    def __init__(self, filename):
        self.filename = filename
        if not os.path.exists(self.filename):
            self._write_data({})  # Start with an empty dict

    def _read_data(self):
        with open(self.filename, 'r', encoding='utf-8') as f:
            return json.load(f)

    def _write_data(self, data):
        with open(self.filename, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=4)

    def add_or_update(self, key_path, value):
        """
        key_path: list of keys like ['root', 'child', 'subchild']
        value: value to be inserted or updated at the path
        """
        data = self._read_data()
        d = data
        for key in key_path[:-1]:
            if key not in d or not isinstance(d[key], dict):
                d[key] = {}
            d = d[key]
        d[key_path[-1]] = value
        self._write_data(data)

    def remove(self, key_path):
        """
        key_path: list of keys like ['root', 'child', 'subchild']
        Removes the key at that path
        """
        data = self._read_data()
        d = data
        for key in key_path[:-1]:
            d = d.get(key, {})
        d.pop(key_path[-1], None)
        self._write_data(data)

    def read(self):
        return self._read_data()

    def append_to_list(self, key_path, item):
        data = self._read_data()
        d = data
        for key in key_path[:-1]:
            d = d.setdefault(key, {})
        last_key = key_path[-1]
        if last_key not in d or not isinstance(d[last_key], list):
            d[last_key] = []
        d[last_key].append(item)
        self._write_data(data)


# if __name__ == "__main__":
#     jfile = NestedJsonFile("my_data.json")

#     # Add nested data
#     jfile.add_or_update(["app", "config", "theme"], "dark")
#     jfile.add_or_update(["app", "version"], "1.0.0")

#     # Remove a nested key
#     # jfile.remove(["app", "config", "theme"])

#     # Read and print data
#     print(json.dumps(jfile.read(), indent=2))
