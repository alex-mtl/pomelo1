## POMELO

Please note:
- Clinic Only one clinic is to be supported
  - A clinic has only one attribute, its name, which also acts as its unique identifier (ID)

- Patient
  - A clinic can have one or several patients
  - A patient has the following attributes:
    - First name
    - Last name
  - The combination of the first and last names acts as an ID

- Provider
  - A clinic can have one or several providers
  - A provider has the following attributes:
    - First name
    - Last name
  - The combination of the first and last names acts as an ID

- Availability
  - An availability is a time-slot during which a provider is able to treat a patient, if the latter
has booked an appointment
  - Each provider has one or several availabilities everyday
  - An availability has the following attributes:
    - Start date and time (timestamp)
    - End date and time (timestamp)
  - Availabilities are 15-minutes time-slots (8:00, 8:15, 8:30, etc.)

- Appointment
  - An availability that's been chosen by a patient is converted into an appointment upon
booking (one availability = one appointment)
  - An appointment has the following attributes:
     - An appointed patient
     - An appointed provider
     - Start date and time (timestamp)
     - End date and time (timestamp)
  - As appointments are booked on availabilities, they are also 15-minutes time-slots
