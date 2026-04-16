import requests

URL = "http://localhost/service.php"

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
        print("Status:", result.get("status"))
        print("Message:", result.get("message"))

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