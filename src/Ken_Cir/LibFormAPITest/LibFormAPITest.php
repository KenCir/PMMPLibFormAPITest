<?php

declare(strict_types=1);

namespace Ken_Cir\LibFormAPITest;

use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentDropdown;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentInput;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentLabel;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentSlider;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentStepSlider;
use Ken_Cir\LibFormAPI\FormContents\CustomForm\ContentToggle;
use Ken_Cir\LibFormAPI\Forms\CustomForm;
use Ken_Cir\LibFormAPI\Forms\ModalForm;
use Ken_Cir\LibFormAPI\Forms\SimpleForm;
use Ken_Cir\LibFormAPI\FormContents\SimpleForm\SimpleFormButton;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class LibFormAPITest extends PluginBase
{
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "このコマンドはサーバー内で実行してください");
            return true;
        }

        if ($command->getName() === "simpleform") {
            new SimpleForm($sender,
                "テスト SimpleForm",
                "テスト",
                [
                    new SimpleFormButton("テスト1", "textures/items/apple", SimpleFormButton::IMAGE_TYPE_PATH)
                ],
                function (Player $player, int $data) {
                    $player->sendMessage("{$data}番が選択されました");
                },
                function (Player $player) {
                    $player->sendMessage("フォームが閉じられました");
                });
        } elseif ($command->getName() === "modalform") {
            new ModalForm($sender,
                "テスト ModalForm",
                "テスト",
                "ボタン1",
                "ボタン2",
                function (Player $player, bool $data) {
                    $player->sendMessage($data ? "ボタン1が選択されました" : "ボタン2が選択されました");
                },
                function (Player $player) {
                    $player->sendMessage("フォームが閉じられました");
                });
        } elseif ($command->getName() === "customform") {
            new CustomForm($sender,
                "テスト CustomForm",
                [
                    // 必須パラメーターでのテスト
                    new ContentLabel("テスト Label"),
                    new ContentToggle("テスト Toggle"),
                    new ContentSlider("テスト Slider", 1, 10),
                    new ContentStepSlider("テスト StepSlider", ["1", "5", "10"]),
                    new ContentDropdown("テスト Dropdown", ["0", "1", "2"]),
                    new ContentInput("テスト Input"),

                    // オプションパラメーターを使用してのテスト
                    new ContentLabel("テスト Label"),
                    new ContentToggle("テスト Toggle", true),
                    new ContentSlider("テスト Slider", 1, 10, 5),
                    new ContentSlider("テスト Slider", 1, 10, 5, 3),
                    new ContentStepSlider("テスト StepSlider", ["1", "5", "10"], 1),
                    new ContentDropdown("テスト Dropdown", ["0", "1", "2"], 2),
                    new ContentInput("テスト Input", "テスト"),
                    new ContentInput("テスト Input", "テスト", "TEST"),
                ],
                function (Player $player, array $data) {
                    $player->sendMessage("サクセス");
                    var_dump($data);
                },
                function (Player $player) {
                    $player->sendMessage("フォームが閉じられました");
                });
        } else return false;

        return true;
    }
}