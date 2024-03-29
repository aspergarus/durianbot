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
                $newWallet = createWallet();
                $userId = saveUser($newWallet['address'], $update->message->chat->id, $newWallet['private_key'], $newWallet['mnemonic'], PAYMENT_WAITING);

                sendImage($tgLog, $update->message->chat->id, $newWallet['address']);
                saveMessage($update->update_id, $update->message->text, 'waiting', time(), $userId);
            }
        },
        function (\Exception $exception) {
            echo 'Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage();
        }
    );
}

function sendImage(TgLog $tgLog, $destId, $address) {
    $imgName = getImageName($address);
    generateImage($address, $imgName);
    $photoFile = new InputFile($imgName);

    $sendPhoto = new SendPhoto();
    $sendPhoto->chat_id = $destId;
    $sendPhoto->photo = $photoFile;
    $sendPhoto->caption = prepareMessage();
    $promise = $tgLog->performApiRequest($sendPhoto);

    $promise->then(function() use ($photoFile, $imgName) {
        fclose($photoFile->getStream());
        cleanImage($imgName);
    });
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
