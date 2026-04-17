import requests

URL = "http://localhost/direct-login.php"

def login():
    print("\n===| LOGIN |===")
    username = input("Enter username: ")
    password = input("Enter password: ")

    data = {
        "username": username,
        "password": password
    }

    try:
        response = requests.post(URL, json=data)
        result = response.json()

        print("\nRESPONSE:")
        print("Status :", result.get("status"))
        print("Message:", result.get("message"))

        if result.get("status") == "SUCCESS":
            user = result.get("data", {})
            print("Name    :", user.get("name"))
            print("Username:", user.get("username"))
            print("Role    :", user.get("role"))

    except Exception as e:
        print("ERROR CONNECTING TO API:", e)


def main():
    while True:
        print("\n===| CONSOLE CLIENT |===")
        print("1. Login")
        print("2. Exit")

        choice = input("CHOOSE AN OPTION: ")

        if choice == "1":
            login()
        elif choice == "2":
            print("EXITING...")
            break
        else:
            print("INVALID CHOICE. TRY AGAIN.")


if __name__ == "__main__":
    main()
