<?php

use unreal4u\TelegramAPI\Abstracts\TraversableCustomType;
use unreal4u\TelegramAPI\Telegram\Methods\GetUpdates;
use unreal4u\TelegramAPI\Telegram\Methods\PinChatMessage;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\Telegram\Methods\SendPhoto;
use unreal4u\TelegramAPI\Telegram\Types\Custom\InputFile;
use unreal4u\TelegramAPI\Telegram\Types\Message;
use unreal4u\TelegramAPI\Telegram\Types\Update;
use unreal4u\TelegramAPI\TgLog;

function saveLastUpdates(TgLog $tgLog) {
    $offset = getLastUpdatesFromDb() ?? 0;

    $getUpdates = new GetUpdates();
    if (!empty($offset)) {
        $getUpdates->offset = $offset + 1;
    }

    $updatePromise = $tgLog->performApiRequest($getUpdates);

    $updatePromise->then(
        function (TraversableCustomType $updatesArray) use ($tgLog) {
            foreach ($updatesArray as $update) {
                /* @var Update $update */
//                print_r([
//                    'updateId' => $update->update_id,
//                    'chatId' => $update->message->chat->id,
//                    'messageId' => $update->message->message_id,
//                    'text' => $update->message->text,
//                ]);

                sendImage($tgLog, $update->message->chat->id, $update->update_id);
                // sendMsg($tgLog, $update->message->chat->id, $update->message->text . " - cсам такий");
                saveInDb($update->update_id, $update->message->text, 'waiting', time());
            }
        },
        function (\Exception $exception) {
            echo 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
        }
    );
}

function sendImage(TgLog $tgLog, $destId, $updateId) {
    $address = getAddress();
    $imgName = getImageName($address);
    generateImage($address, $imgName);

    $sendPhoto = new SendPhoto();
    $sendPhoto->chat_id = $destId;
    $sendPhoto->photo = new InputFile($imgName);
    $sendPhoto->caption = prepareMessage($updateId);
    $tgLog->performApiRequest($sendPhoto);
}

function sendMsg(TgLog $tgLog, $destId, $text) {
    $sendMessage = new SendMessage();
    $sendMessage->chat_id = $destId;
    $sendMessage->text = $text;

    return $tgLog->performApiRequest($sendMessage);
}

function sendMessageWithPin(TgLog $tgLog, $text) {
    $promise = sendMsg($tgLog, A_USER_CHAT_ID, $text);

    $promise->then(function (Message $message) use ($tgLog) {
        $pinMessage = new PinChatMessage();
        $pinMessage->chat_id = A_USER_CHAT_ID;
        $pinMessage->message_id = $message->message_id;

        $tgLog->performApiRequest($pinMessage);
    });
}
